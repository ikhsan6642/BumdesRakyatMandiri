<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessUnit;
use App\Models\Transaction;
use App\Models\CfdEvent;
use App\Models\PamsimasUsage;
use App\Models\PamsimasCustomer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    {
        // Jika pengguna adalah admin unit, langsung alihkan ke halaman khusus unit usahanya
        if (Auth::user()->role === 'admin_unit') {
            return redirect(Auth::user()->getHomeRoute());
        }

        // 1. Total Konsolidasi Keuangan
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        // 2. Performa Tiap Unit Usaha
        $units = BusinessUnit::with(['transactions'])->get()->map(function ($unit) {
            $income = $unit->transactions->where('type', 'income')->sum('amount');
            $expense = $unit->transactions->where('type', 'expense')->sum('amount');
            $profit = $income - $expense;
            return [
                'code' => $unit->code,
                'name' => $unit->name,
                'description' => $unit->description,
                'manager_name' => $unit->manager_name,
                'manager_phone' => $unit->manager_phone,
                'icon' => $unit->icon,
                'color' => $unit->color,
                'income' => $income,
                'expense' => $expense,
                'net_profit' => $profit,
            ];
        });

        // 3. Data KPI Operasional Spesifik
        $latestCfd = CfdEvent::latest('event_date')->first();
        $totalCfdVendors = \App\Models\CfdVendor::where('status', 'aktif')->count();

        $activePamsimasCustomers = PamsimasCustomer::where('status', 'aktif')->count();
        $unpaidWaterBills = PamsimasUsage::where('payment_status', 'belum_lunas')->count();
        $totalUnpaidAmount = PamsimasUsage::where('payment_status', 'belum_lunas')->sum('total_amount');

        // 4. Data Transaksi Terakhir
        $recentTransactions = Transaction::with('businessUnit')
            ->latest('transaction_date')
            ->latest('id')
            ->take(8)
            ->get();

        // 5. Data Chart Bulanan (Tahun Ini)
        $currentYear = Carbon::now()->year;
        $monthlyData = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        for ($m = 1; $m <= 12; $m++) {
            $mIncome = Transaction::where('type', 'income')
                ->whereYear('transaction_date', $currentYear)
                ->whereMonth('transaction_date', $m)
                ->sum('amount');

            $mExpense = Transaction::where('type', 'expense')
                ->whereYear('transaction_date', $currentYear)
                ->whereMonth('transaction_date', $m)
                ->sum('amount');

            $monthlyData[] = [
                'month' => $months[$m - 1],
                'income' => (float) $mIncome,
                'expense' => (float) $mExpense,
            ];
        }

        // 6. Komposisi Pendapatan per Unit untuk Pie Chart
        $unitRevenueDistribution = [
            'cfd' => (float) Transaction::where('unit_code', 'cfd')->where('type', 'income')->sum('amount'),
            'pamsimas' => (float) Transaction::where('unit_code', 'pamsimas')->where('type', 'income')->sum('amount'),
        ];

        return view('dashboard.index', compact(
            'totalIncome',
            'totalExpense',
            'netProfit',
            'units',
            'latestCfd',
            'totalCfdVendors',
            'activePamsimasCustomers',
            'unpaidWaterBills',
            'totalUnpaidAmount',
            'recentTransactions',
            'monthlyData',
            'unitRevenueDistribution'
        ));
    }
}
