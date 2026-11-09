<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PamsimasSetting;
use App\Models\PamsimasCustomer;
use App\Models\PamsimasUsage;
use App\Models\PamsimasMaintenance;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PamsimasController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'bills');
        $period = $request->get('period', Carbon::now()->format('Y-m'));

        $setting = PamsimasSetting::getActiveSetting();

        // 1. Tagihan Periode Tertentu
        $billQuery = PamsimasUsage::with('customer')->where('billing_period', $period);
        if ($request->filled('status')) {
            $billQuery->where('payment_status', $request->status);
        }
        $usages = $billQuery->latest('id')->paginate(15, ['*'], 'bill_page')->withQueryString();

        // 2. Data Pelanggan
        $custQuery = PamsimasCustomer::with('latestUsage');
        if ($request->filled('hamlet')) {
            $custQuery->where('hamlet', $request->hamlet);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $custQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('customer_number', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }
        $customers = $custQuery->orderBy('customer_number')->paginate(15, ['*'], 'cust_page')->withQueryString();

        // 3. Biaya Operasional / Pemeliharaan
        $maintenances = PamsimasMaintenance::latest('expense_date')->paginate(10, ['*'], 'mnt_page');

        // Ringkasan Finansial Pamsimas Periode Ini
        $totalBilled = PamsimasUsage::where('billing_period', $period)->sum('total_amount');
        $totalCollected = PamsimasUsage::where('billing_period', $period)->where('payment_status', 'lunas')->sum('total_amount');
        $totalUnpaid = PamsimasUsage::where('billing_period', $period)->where('payment_status', 'belum_lunas')->sum('total_amount');
        $totalUsageM3 = PamsimasUsage::where('billing_period', $period)->sum('usage_m3');

        $totalMaintenanceCost = PamsimasMaintenance::sum('cost');
        $allTimeWaterRevenue = PamsimasUsage::where('payment_status', 'lunas')->sum('total_amount');
        $allTimeNetProfit = $allTimeWaterRevenue - $totalMaintenanceCost;

        $availablePeriods = PamsimasUsage::select('billing_period')->distinct()->orderByDesc('billing_period')->pluck('billing_period');
        if (!$availablePeriods->contains($period)) {
            $availablePeriods->prepend($period);
        }

        $allCustomers = PamsimasCustomer::where('status', 'aktif')->orderBy('name')->get();
        $hamlets = PamsimasCustomer::select('hamlet')->distinct()->pluck('hamlet');

        return view('unit_pamsimas.index', compact(
            'tab',
            'period',
            'setting',
            'usages',
            'customers',
            'maintenances',
            'totalBilled',
            'totalCollected',
            'totalUnpaid',
            'totalUsageM3',
            'totalMaintenanceCost',
            'allTimeWaterRevenue',
            'allTimeNetProfit',
            'availablePeriods',
            'allCustomers',
            'hamlets'
        ));
    }

    public function storeReading(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:pamsimas_customers,id',
            'billing_period' => 'required|date_format:Y-m',
            'previous_meter' => 'required|integer|min:0',
            'current_meter' => 'required|integer|gte:previous_meter',
            'reading_date' => 'required|date',
        ]);

        $customer = PamsimasCustomer::findOrFail($validated['customer_id']);
        $setting = PamsimasSetting::getActiveSetting();

        // Cek duplikasi tagihan pada periode yang sama
        $exists = PamsimasUsage::where('customer_id', $customer->id)
            ->where('billing_period', $validated['billing_period'])
            ->exists();

        if ($exists) {
            return back()->with('error', "Pelanggan {$customer->name} sudah memiliki tagihan untuk periode {$validated['billing_period']}.");
        }

        $usageM3 = $validated['current_meter'] - $validated['previous_meter'];

        // Tentukan tarif per m3 sesuai kategori pelanggan
        $ratePerM3 = match ($customer->category) {
            'sosial' => $setting->rate_per_m3_social,
            'usaha' => $setting->rate_per_m3_business,
            default => $setting->rate_per_m3_household,
        };

        $waterCharge = $usageM3 * $ratePerM3;
        $subscriptionFee = $setting->subscription_fee;
        $totalAmount = $waterCharge + $subscriptionFee;

        // Jatuh tempo tgl due_date_day bulan tersebut
        $parts = explode('-', $validated['billing_period']);
        $dueDate = Carbon::createFromDate((int) $parts[0], (int) $parts[1], $setting->due_date_day)->toDateString();

        $count = PamsimasUsage::where('billing_period', $validated['billing_period'])->count() + 1;
        $billNumber = sprintf('BILL-PAM-%s-%04d', str_replace('-', '', $validated['billing_period']), $count);

        PamsimasUsage::create([
            'customer_id' => $customer->id,
            'billing_period' => $validated['billing_period'],
            'bill_number' => $billNumber,
            'previous_meter' => $validated['previous_meter'],
            'current_meter' => $validated['current_meter'],
            'usage_m3' => $usageM3,
            'rate_per_m3' => $ratePerM3,
            'water_charge' => $waterCharge,
            'subscription_fee' => $subscriptionFee,
            'late_fee' => 0,
            'total_amount' => $totalAmount,
            'due_date' => $dueDate,
            'payment_status' => 'belum_lunas',
            'reading_date' => $validated['reading_date'],
        ]);

        return back()->with('success', "Pencatatan meteran & tagihan {$billNumber} untuk {$customer->name} ({$usageM3} m³) berhasil dibuat!");
    }

    public function payBill(Request $request, $id)
    {
        $usage = PamsimasUsage::with('customer')->findOrFail($id);

        $validated = $request->validate([
            'payment_method' => 'required|in:tunai,transfer_bank',
            'late_fee' => 'nullable|numeric|min:0',
        ]);

        $lateFee = $validated['late_fee'] ?? 0;
        $finalTotal = $usage->water_charge + $usage->subscription_fee + $lateFee;

        $receiptNumber = sprintf('KW-PAM-%s-%04d', str_replace('-', '', $usage->billing_period), $usage->id);

        $usage->update([
            'late_fee' => $lateFee,
            'total_amount' => $finalTotal,
            'payment_status' => 'lunas',
            'paid_at' => Carbon::now(),
            'payment_method' => $validated['payment_method'],
            'receipt_number' => $receiptNumber,
            'collected_by' => Auth::user()->name ?? 'Petugas Pamsimas',
        ]);

        // Sinkronisasi otomatis ke Buku Kas Umum Keuangan BUMDes
        $txCode = 'BKM-' . $receiptNumber;

        Transaction::create([
            'transaction_code' => $txCode,
            'unit_code' => 'pamsimas',
            'type' => 'income',
            'amount' => $finalTotal,
            'transaction_date' => Carbon::now()->toDateString(),
            'payment_method' => $validated['payment_method'],
            'reference_type' => 'pamsimas',
            'reference_id' => $usage->id,
            'recipient_or_payer' => $usage->customer->name,
            'description' => "Pembayaran Tagihan Air Pamsimas {$usage->customer->customer_number} ({$usage->customer->name}) Periode {$usage->billing_period} - Pemakaian {$usage->usage_m3} m³",
            'receipt_number' => $receiptNumber,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', "Pembayaran tagihan {$receiptNumber} berhasil diproses!");
    }

    public function printBill($id)
    {
        $usage = PamsimasUsage::with('customer')->findOrFail($id);
        $setting = PamsimasSetting::getActiveSetting();
        return view('unit_pamsimas.print_bill', compact('usage', 'setting'));
    }

    public function storeCustomer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:50',
            'hamlet' => 'required|string|max:255',
            'rt_rw' => 'required|string|max:50',
            'address' => 'nullable|string|max:255',
            'category' => 'required|in:rumah_tangga,sosial,usaha',
            'initial_meter' => 'required|integer|min:0',
            'installation_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $count = PamsimasCustomer::count() + 1;
        while (PamsimasCustomer::where('customer_number', sprintf('PAM-%04d', $count))->exists()) {
            $count++;
        }
        $validated['customer_number'] = sprintf('PAM-%04d', $count);
        $validated['status'] = 'aktif';

        $customer = PamsimasCustomer::create($validated);
        return back()->with('success', "Pelanggan baru {$customer->name} ({$customer->customer_number}) berhasil didaftarkan!");
    }

    public function updateCustomer(Request $request, $id)
    {
        $customer = PamsimasCustomer::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:50',
            'hamlet' => 'required|string|max:255',
            'rt_rw' => 'required|string|max:50',
            'address' => 'nullable|string|max:255',
            'category' => 'required|in:rumah_tangga,sosial,usaha',
            'initial_meter' => 'required|integer|min:0',
            'installation_date' => 'nullable|date',
            'status' => 'required|in:aktif,nonaktif,segel',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validated);

        return back()->with('success', "Data pelanggan {$customer->customer_number} berhasil diperbarui.");
    }

    public function destroyCustomer($id)
    {
        $customer = PamsimasCustomer::findOrFail($id);

        if ($customer->usages()->exists()) {
            return back()->with('error', "Pelanggan {$customer->customer_number} tidak dapat dihapus karena memiliki riwayat tagihan. Ubah statusnya menjadi nonaktif untuk mempertahankan riwayat.");
        }

        $customerName = $customer->name;
        $customer->delete();

        return back()->with('success', "Data pelanggan {$customerName} berhasil dihapus.");
    }

    public function storeMaintenance(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:listrik_pompa,perbaikan_pipa,kaporit_kimia,honor_operator,penggantian_meteran,lainnya',
            'cost' => 'required|numeric|min:1',
            'expense_date' => 'required|date',
            'technician_or_vendor' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $count = PamsimasMaintenance::count() + 1;
        $validated['maintenance_code'] = sprintf('MNT-PAM-%s-%03d', Carbon::parse($validated['expense_date'])->format('Ym'), $count);

        $mnt = PamsimasMaintenance::create($validated);

        // Auto catat ke Kas Transaksi BUMDes
        $code = sprintf('BKK-PAM-%s-%03d', Carbon::parse($validated['expense_date'])->format('Ym'), Transaction::where('unit_code', 'pamsimas')->where('type', 'expense')->count() + 1);

        Transaction::create([
            'transaction_code' => $code,
            'unit_code' => 'pamsimas',
            'type' => 'expense',
            'amount' => $validated['cost'],
            'transaction_date' => $validated['expense_date'],
            'payment_method' => 'tunai',
            'reference_type' => 'pamsimas',
            'reference_id' => $mnt->id,
            'recipient_or_payer' => $validated['technician_or_vendor'] ?: 'Vendor / Teknisi Pamsimas',
            'description' => "Biaya Operasional Pamsimas: {$validated['title']}",
            'receipt_number' => $validated['maintenance_code'],
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', "Biaya pemeliharaan {$validated['maintenance_code']} berhasil dicatat!");
    }

    public function updateSettings(Request $request)
    {
        $setting = PamsimasSetting::getActiveSetting();
        $validated = $request->validate([
            'rate_per_m3_social' => 'required|numeric|min:0',
            'rate_per_m3_household' => 'required|numeric|min:0',
            'rate_per_m3_business' => 'required|numeric|min:0',
            'subscription_fee' => 'required|numeric|min:0',
            'late_fee' => 'required|numeric|min:0',
            'due_date_day' => 'required|integer|min:1|max:28',
            'water_source_info' => 'nullable|string',
        ]);

        $setting->update($validated);
        return back()->with('success', 'Pengaturan tarif & abonemen Pamsimas berhasil diperbarui!');
    }
}
