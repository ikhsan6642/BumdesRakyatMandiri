<?php

namespace App\Http\Controllers;

use App\Models\CfdEvent;
use App\Models\CfdTicket;
use App\Models\CfdVendor;
use App\Models\CfdZone;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CfdController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'events');

        $events = CfdEvent::latest('event_date')->get();
        foreach ($events as $event) {
            if ($event->syncStatusToSchedule() && $event->status === 'selesai') {
                $this->syncEventTransactions($event);
            }
        }
        $tickets = CfdTicket::successfullyRecorded()
            ->with(['event', 'vendor', 'zone'])
            ->latest('created_at')
            ->paginate(15, ['*'], 'tkt_page')
            ->withQueryString();
        $ticketedVendorIdsByEvent = CfdTicket::successfullyRecorded()
            ->whereNotNull('vendor_id')
            ->get(['event_id', 'vendor_id'])
            ->groupBy('event_id')
            ->map(fn ($eventTickets) => $eventTickets->pluck('vendor_id')->map(fn ($vendorId) => (int) $vendorId)->unique()->values());
        $vendors = CfdVendor::with('preferredZone')->latest('id')->paginate(15, ['*'], 'vnd_page');
        $zones = CfdZone::withCount(['vendors', 'tickets'])->get();
        $availableSpotsByVendor = $vendors->getCollection()->mapWithKeys(function (CfdVendor $vendor) use ($zones) {
            $spotsByZone = $zones->mapWithKeys(function (CfdZone $zone) use ($vendor) {
                $availableSpots = $zone->getAvailableSpotNumbers($vendor->id);
                if ((int) $vendor->preferred_zone_id === (int) $zone->id && $vendor->spot_number) {
                    $availableSpots->prepend($vendor->spot_number);
                }

                return [$zone->id => $availableSpots->filter()->unique()->values()->all()];
            });

            return [$vendor->id => $spotsByZone->all()];
        });
        $availableSpotsByZone = $zones->mapWithKeys(
            fn (CfdZone $zone) => [$zone->id => $zone->getAvailableSpotNumbers()->all()]
        );

        $totalStallRevenue = CfdEvent::sum('total_stall_revenue');
        $activeVendorsCount = CfdVendor::where('status', 'aktif')->count();
        $latestEvent = CfdEvent::latest('event_date')->first();

        return view('unit_cfd.index', compact(
            'tab',
            'events',
            'tickets',
            'ticketedVendorIdsByEvent',
            'vendors',
            'zones',
            'availableSpotsByVendor',
            'availableSpotsByZone',
            'totalStallRevenue',
            'activeVendorsCount',
            'latestEvent'
        ));
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'collection_start_time' => 'required|date_format:H:i',
            'collection_end_time' => 'required|date_format:H:i|after:collection_start_time',
            'parking_revenue' => 'nullable|numeric|min:0',
            'cleanliness_cost' => 'nullable|numeric|min:0',
            'security_cost' => 'nullable|numeric|min:0',
            'sound_electricity_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $date = Carbon::parse($validated['event_date']);

        $validated['event_code'] = $this->generateEventCode($date);
        $validated['parking_revenue'] = $validated['parking_revenue'] ?? 0;
        $validated['cleanliness_cost'] = $validated['cleanliness_cost'] ?? 0;
        $validated['security_cost'] = $validated['security_cost'] ?? 0;
        $validated['sound_electricity_cost'] = $validated['sound_electricity_cost'] ?? 0;
        $validated['status'] = 'terjadwal';

        $event = CfdEvent::create($validated);
        $event->syncStatusToSchedule();
        $event->calculateNetRevenue();
        if ($event->status === 'selesai') {
            $this->syncEventTransactions($event);
        }

        return back()->with('success', "Event mingguan {$event->name} ({$event->event_code}) berhasil dibuat!");
    }

    public function updateEventCosts(Request $request, $id)
    {
        $event = CfdEvent::findOrFail($id);
        $validated = $request->validate([
            'parking_revenue' => 'required|numeric|min:0',
            'cleanliness_cost' => 'required|numeric|min:0',
            'security_cost' => 'required|numeric|min:0',
            'sound_electricity_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $event->update($validated);
        $event->syncStatusToSchedule();
        $event->calculateNetRevenue();

        if ($event->status === 'selesai') {
            $this->syncEventTransactions($event);
        }

        return back()->with('success', "Rekap biaya operasional & parkir {$event->name} berhasil diperbarui!");
    }

    public function destroyEvent($id)
    {
        $event = CfdEvent::findOrFail($id);

        DB::transaction(function () use ($event) {
            $tickets = $event->tickets()->get(['id', 'ticket_number']);
            $ticketNumbers = $tickets->pluck('ticket_number');
            $ticketCodes = $ticketNumbers->map(fn (string $number) => 'BKM-'.$number);
            $eventCodes = [
                sprintf('BKM-CFD-P%s-%02d', Carbon::parse($event->event_date)->format('Ym'), $event->id),
                sprintf('BKK-CFD-OPS%s-%02d', Carbon::parse($event->event_date)->format('Ym'), $event->id),
            ];

            Transaction::where('unit_code', 'cfd')
                ->where(function ($query) use ($ticketNumbers, $ticketCodes, $eventCodes) {
                    $query->whereIn('receipt_number', $ticketNumbers)
                        ->orWhereIn('transaction_code', $ticketCodes->merge($eventCodes));
                })
                ->delete();

            $event->delete();
        });

        return back()->with('success', "Event CFD {$event->name} dan data transaksi terkait berhasil dihapus.");
    }

    public function storeTicket(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:cfd_events,id',
            'vendor_id' => 'nullable|exists:cfd_vendors,id',
            'vendor_name_manual' => 'nullable|string|max:255',
            'zone_id' => 'required|exists:cfd_zones,id',
            'spot_number' => 'required|string|max:50',
            'fee_amount' => 'nullable|numeric|min:0',
            'electricity_fee' => 'nullable|numeric|min:0',
            'cleanliness_fee' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:lunas,belum_bayar',
            'collected_by' => 'nullable|string|max:255',
        ]);

        if (! empty($validated['vendor_id'])) {
            $vendor = CfdVendor::where('status', 'aktif')->find($validated['vendor_id']);
            if (! $vendor || ! $vendor->preferred_zone_id || ! $vendor->spot_number) {
                throw ValidationException::withMessages([
                    'vendor_id' => 'Pedagang terdaftar belum memiliki zona dan nomor lapak aktif.',
                ]);
            }

            $validated['zone_id'] = $vendor->preferred_zone_id;
            $validated['spot_number'] = $vendor->spot_number;
        }

        $zone = CfdZone::findOrFail($validated['zone_id']);
        $validated['fee_amount'] = (float) $zone->default_fee;

        $spotAlreadyUsed = CfdTicket::where('event_id', $validated['event_id'])
            ->where('zone_id', $validated['zone_id'])
            ->where('spot_number', $validated['spot_number'])
            ->exists();
        if ($spotAlreadyUsed) {
            throw ValidationException::withMessages([
                'spot_number' => 'Nomor lapak tersebut sudah digunakan pada event ini.',
            ]);
        }

        $event = CfdEvent::findOrFail($validated['event_id']);
        $ticket = DB::transaction(function () use ($validated, $event) {
            $validated['electricity_fee'] = $validated['electricity_fee'] ?? 0;
            $validated['cleanliness_fee'] = $validated['cleanliness_fee'] ?? 0;
            $validated['total_amount'] = $validated['fee_amount'] + $validated['electricity_fee'] + $validated['cleanliness_fee'];

            $dateStr = Carbon::parse($event->event_date)->format('Ymd');
            $validated['ticket_number'] = $this->generateTicketNumber($dateStr);
            $validated['paid_at'] = $validated['payment_status'] === 'lunas' ? Carbon::now() : null;
            $validated['collected_by'] = ($validated['collected_by'] ?? null) ?: (Auth::user()->name ?? 'Petugas CFD');

            $ticket = CfdTicket::create($validated);

            if ($validated['payment_status'] === 'lunas') {
                $vendorName = $ticket->vendor ? $ticket->vendor->name : ($ticket->vendor_name_manual ?: 'Pedagang Lapak '.$ticket->spot_number);

                Transaction::create([
                    'transaction_code' => 'BKM-'.$ticket->ticket_number,
                    'unit_code' => 'cfd',
                    'type' => 'income',
                    'amount' => $ticket->total_amount,
                    'transaction_date' => $event->event_date,
                    'payment_method' => 'tunai',
                    'reference_type' => 'cfd',
                    'reference_id' => $ticket->id,
                    'recipient_or_payer' => $vendorName,
                    'description' => "Retribusi Lapak {$ticket->spot_number} ({$vendorName}) - {$ticket->ticket_number}",
                    'receipt_number' => $ticket->ticket_number,
                    'created_by' => Auth::id(),
                ]);
            }

            $event->calculateNetRevenue();

            return $ticket;
        });

        return back()->with('success', "Karcis {$ticket->ticket_number} berhasil dicatat!");
    }

    public function storeVendor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'preferred_zone_id' => 'required|exists:cfd_zones,id',
            'spot_number' => 'required|string|max:50',
            'category' => 'required|in:kuliner_makanan,kuliner_minuman,fashion_pakaian,kerajinan_souvenir,mainan_hiburan,pertanian_organik,lainnya',
            'origin_village' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
            'notes' => 'nullable|string',
        ]);

        $zone = CfdZone::findOrFail($validated['preferred_zone_id']);
        if (! $zone->getAvailableSpotNumbers()->contains($validated['spot_number'])) {
            throw ValidationException::withMessages([
                'spot_number' => 'Nomor lapak tidak tersedia di zona yang dipilih. Silakan pilih nomor lain.',
            ]);
        }

        $count = CfdVendor::count() + 1;
        $validated['vendor_code'] = sprintf('CFD-V-%03d', $count);

        CfdVendor::create($validated);

        return back()->with('success', "Pedagang UMKM {$validated['name']} ({$validated['vendor_code']}) berhasil didaftarkan!");
    }

    public function updateVendor(Request $request, $id)
    {
        $vendor = CfdVendor::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'preferred_zone_id' => 'required|exists:cfd_zones,id',
            'spot_number' => 'required|string|max:50',
            'category' => 'required|in:kuliner_makanan,kuliner_minuman,fashion_pakaian,kerajinan_souvenir,mainan_hiburan,pertanian_organik,lainnya',
            'origin_village' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
            'notes' => 'nullable|string',
        ]);

        $zone = CfdZone::findOrFail($validated['preferred_zone_id']);
        if (
            $validated['status'] === 'aktif'
            && ! $zone->getAvailableSpotNumbers($vendor->id)->contains($validated['spot_number'])
        ) {
            throw ValidationException::withMessages([
                'spot_number' => 'Nomor lapak tidak tersedia di zona yang dipilih. Silakan pilih nomor lain.',
            ]);
        }

        $vendor->update($validated);

        return back()->with('success', "Data pedagang UMKM {$vendor->name} berhasil diperbarui.");
    }

    public function destroyVendor($id)
    {
        $vendor = CfdVendor::findOrFail($id);
        $vendorName = $vendor->name;
        $vendor->delete();

        return back()->with('success', "Pedagang UMKM {$vendorName} berhasil dihapus. Riwayat karcis tetap tersimpan.");
    }

    public function storeZone(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:cfd_zones,code',
            'default_fee' => 'required|numeric|min:0',
            'capacity_spots' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        CfdZone::create($validated);

        return back()->with('success', "Zona Lapak {$validated['name']} berhasil ditambahkan!");
    }

    public function updateZone(Request $request, $id)
    {
        $zone = CfdZone::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:cfd_zones,code,'.$zone->id,
            'default_fee' => 'required|numeric|min:0',
            'capacity_spots' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $oldPrefix = preg_replace('/^Z-/i', '', $zone->code);
        $newPrefix = preg_replace('/^Z-/i', '', $validated['code']);
        $assignedSpots = $zone->vendors()
            ->where('status', 'aktif')
            ->whereNotNull('spot_number')
            ->pluck('spot_number');
        foreach ($assignedSpots as $spotNumber) {
            if (
                preg_match('/^'.preg_quote($oldPrefix, '/').'-([0-9]+)$/i', $spotNumber, $matches)
                && (int) $matches[1] > $validated['capacity_spots']
            ) {
                throw ValidationException::withMessages([
                    'capacity_spots' => 'Kapasitas tidak boleh lebih kecil dari nomor lapak aktif yang sudah dialokasikan.',
                ]);
            }
        }

        DB::transaction(function () use ($zone, $validated, $oldPrefix, $newPrefix) {
            if ($oldPrefix !== $newPrefix) {
                foreach ($zone->vendors()->whereNotNull('spot_number')->get() as $vendor) {
                    if (preg_match('/^'.preg_quote($oldPrefix, '/').'-([0-9]+)$/i', $vendor->spot_number, $matches)) {
                        $vendor->spot_number = $newPrefix.'-'.$matches[1];
                        $vendor->save();
                    }
                }
            }

            $zone->update($validated);
        });

        return back()->with('success', "Zona Lapak {$zone->name} berhasil diperbarui.");
    }

    public function destroyZone($id)
    {
        $zone = CfdZone::withCount(['vendors', 'tickets'])->findOrFail($id);

        if ($zone->tickets_count > 0 || $zone->vendors_count > 0) {
            return back()->with('error', 'Zona tidak dapat dihapus selama masih memiliki karcis atau pedagang terdaftar. Pindahkan data terkait terlebih dahulu.');
        }

        $zoneName = $zone->name;
        $zone->delete();

        return back()->with('success', "Zona Lapak {$zoneName} berhasil dihapus.");
    }

    private function syncEventTransactions(CfdEvent $event): void
    {
        if ($event->parking_revenue > 0) {
            $codeParkir = sprintf('BKM-CFD-P%s-%02d', Carbon::parse($event->event_date)->format('Ym'), $event->id);

            Transaction::updateOrCreate(
                ['transaction_code' => $codeParkir],
                [
                    'unit_code' => 'cfd',
                    'type' => 'income',
                    'amount' => $event->parking_revenue,
                    'transaction_date' => $event->event_date,
                    'payment_method' => 'tunai',
                    'reference_type' => 'cfd',
                    'reference_id' => $event->id,
                    'recipient_or_payer' => 'Pengunjung Parkir CFD',
                    'description' => "Pendapatan Parkir Kendaraan {$event->name}",
                    'receipt_number' => $codeParkir,
                    'created_by' => Auth::id(),
                ]
            );
        }

        $totalCost = $event->cleanliness_cost + $event->security_cost + $event->sound_electricity_cost;
        if ($totalCost > 0) {
            $codeBeban = sprintf('BKK-CFD-OPS%s-%02d', Carbon::parse($event->event_date)->format('Ym'), $event->id);

            Transaction::updateOrCreate(
                ['transaction_code' => $codeBeban],
                [
                    'unit_code' => 'cfd',
                    'type' => 'expense',
                    'amount' => $totalCost,
                    'transaction_date' => $event->event_date,
                    'payment_method' => 'tunai',
                    'reference_type' => 'cfd',
                    'reference_id' => $event->id,
                    'recipient_or_payer' => 'Petugas Kebersihan, Keamanan & Teknisi CFD',
                    'description' => "Operasional Kebersihan, Linmas & Listrik {$event->name}",
                    'receipt_number' => $codeBeban,
                    'created_by' => Auth::id(),
                ]
            );
        }
    }

    private function generateEventCode(Carbon $date): string
    {
        $baseCode = sprintf('CFD-%s-W%s', $date->format('Y'), $date->weekOfYear);
        $eventCode = $baseCode;
        $suffix = 2;

        while (CfdEvent::where('event_code', $eventCode)->exists()) {
            $eventCode = $baseCode.'-'.$suffix++;
        }

        return $eventCode;
    }

    private function generateTicketNumber(string $date): string
    {
        $baseCode = 'KRC-'.$date;
        $sequence = CfdTicket::where('ticket_number', 'like', $baseCode.'-%')->count() + 1;
        $ticketNumber = sprintf('%s-%03d', $baseCode, $sequence);

        while (CfdTicket::where('ticket_number', $ticketNumber)->exists()) {
            $ticketNumber = sprintf('%s-%03d', $baseCode, ++$sequence);
        }

        return $ticketNumber;
    }
}
