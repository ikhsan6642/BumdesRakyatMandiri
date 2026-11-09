<?php

namespace App\Http\Controllers;

use App\Models\BusinessUnit;
use App\Models\CfdEvent;
use App\Models\CfdTicket;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    /**
     * Buku Kas Umum & Transaksi
     */
    public function transactions(Request $request)
    {
        $user = Auth::user();
        $query = Transaction::query();

        if ($user && $user->role === 'admin_unit') {
            $query->where('unit_code', $user->unit_code);
            $units = BusinessUnit::where('code', $user->unit_code)->get();
        } else {
            if ($request->filled('unit_code')) {
                $query->where('unit_code', $request->unit_code);
            }
            $units = BusinessUnit::all();
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('recipient_or_payer', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest('transaction_date')->latest('id')->paginate(15)->withQueryString();

        $totalIncome = (clone $query)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $query)->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return view('finance.transactions', compact('transactions', 'units', 'totalIncome', 'totalExpense', 'balance'));
    }

    /**
     * Simpan Transaksi Kas Masuk / Kas Keluar
     */
    public function storeTransaction(Request $request)
    {
        $validated = $request->validate([
            'unit_code' => 'required|exists:business_units,code',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'payment_method' => 'required|in:tunai,transfer_bank',
            'recipient_or_payer' => 'nullable|string|max:255',
            'description' => 'required|string',
            'receipt_number' => 'nullable|string|max:100',
        ]);

        if (Auth::user()->role === 'admin_unit') {
            $validated['unit_code'] = Auth::user()->unit_code;
        }

        $prefix = $validated['type'] === 'income' ? 'BKM' : 'BKK';
        $period = Carbon::parse($validated['transaction_date'])->format('Ym');
        $lastSequence = Transaction::where('transaction_code', 'like', "{$prefix}-{$period}-%")
            ->pluck('transaction_code')
            ->map(function ($code) use ($prefix, $period) {
                return preg_match('/^'.preg_quote($prefix, '/').'-'.$period.'-(\d+)$/', $code, $matches)
                    ? (int) $matches[1]
                    : 0;
            })
            ->max();
        $sequence = $lastSequence + 1;
        $transactionCode = sprintf('%s-%s-%03d', $prefix, $period, $sequence);

        while (Transaction::where('transaction_code', $transactionCode)->exists()) {
            $sequence++;
            $transactionCode = sprintf('%s-%s-%03d', $prefix, $period, $sequence);
        }

        Transaction::create([
            'transaction_code' => $transactionCode,
            'unit_code' => $validated['unit_code'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'payment_method' => $validated['payment_method'],
            'recipient_or_payer' => $validated['recipient_or_payer'],
            'description' => $validated['description'],
            'receipt_number' => $validated['receipt_number'] ?? $transactionCode,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', "Transaksi {$transactionCode} berhasil dicatat!");
    }

    /**
     * Hapus Transaksi
     */
    public function destroyTransaction($id)
    {
        $tx = Transaction::findOrFail($id);
        $code = $tx->transaction_code;
        $ticket = null;

        DB::transaction(function () use ($tx, &$ticket) {
            if ($tx->unit_code === 'cfd' && $tx->type === 'income') {
                if ($tx->reference_type === 'cfd' && $tx->reference_id) {
                    $ticket = CfdTicket::find($tx->reference_id);
                }

                if (! $ticket && $tx->receipt_number) {
                    $ticket = CfdTicket::where('ticket_number', $tx->receipt_number)->first();
                }
            }

            $tx->delete();

            if ($ticket) {
                CfdEvent::find($ticket->event_id)?->calculateNetRevenue();
            }
        });

        return back()->with('success', "Transaksi {$code} berhasil dihapus.");
    }

    /**
     * Cetak Bukti Kas / Kwitansi Satuan
     */
    public function printReceipt($id)
    {
        $transaction = Transaction::with(['businessUnit', 'creator'])->findOrFail($id);

        return view('finance.print_receipt', compact('transaction'));
    }

    /**
     * Laporan laba rugi konsolidasi.
     */
    public function reports(Request $request)
    {
        $validated = $request->validate([
            'week' => ['nullable', 'regex:/^\d{4}-W(?:0[1-9]|[1-4][0-9]|5[0-3])$/'],
        ]);
        $selectedWeek = $validated['week'] ?? '';
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', null);
        $units = BusinessUnit::all();
        $unitCode = 'all';

        if (Auth::user()->role === 'admin_unit') {
            $unitCode = Auth::user()->unit_code;
        } else {
            $unitCode = $request->get('unit_code', 'all');
        }

        if ($unitCode === 'pamsimas') {
            $selectedWeek = '';
        }

        $txQuery = Transaction::query();
        $weekStart = null;
        $weekEnd = null;
        if ($selectedWeek !== '') {
            [$weekYear, $weekNumber] = sscanf($selectedWeek, '%d-W%d');
            $weekStart = Carbon::now()->setISODate((int) $weekYear, (int) $weekNumber)->startOfWeek(Carbon::MONDAY)->startOfDay();
            $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();
            $txQuery->whereBetween('transaction_date', [$weekStart, $weekEnd]);
        } else {
            $txQuery->whereYear('transaction_date', $year);
            if ($month) {
                $txQuery->whereMonth('transaction_date', $month);
            }
        }

        if ($unitCode !== 'all') {
            $txQuery->where('unit_code', $unitCode);
        }

        $unitNames = $units->pluck('name', 'code');
        $revenueTransactions = (clone $txQuery)->where('type', 'income')
            ->selectRaw('unit_code, SUM(amount) as total')
            ->groupBy('unit_code')
            ->get()
            ->map(function ($transaction) use ($unitNames) {
                $transaction->unit_name = $unitNames[$transaction->unit_code] ?? strtoupper($transaction->unit_code);

                return $transaction;
            });
        $expenseTransactions = (clone $txQuery)->where('type', 'expense')
            ->selectRaw('unit_code, SUM(amount) as total')
            ->groupBy('unit_code')
            ->get()
            ->map(function ($transaction) use ($unitNames) {
                $transaction->unit_name = $unitNames[$transaction->unit_code] ?? strtoupper($transaction->unit_code);

                return $transaction;
            });

        $totalRevenue = $revenueTransactions->sum('total');
        $totalExpense = $expenseTransactions->sum('total');
        $netProfit = $totalRevenue - $totalExpense;

        return view('finance.reports', compact(
            'year',
            'month',
            'selectedWeek',
            'weekStart',
            'weekEnd',
            'unitCode',
            'revenueTransactions',
            'expenseTransactions',
            'totalRevenue',
            'totalExpense',
            'netProfit',
            'units'
        ));
    }
}
