@extends('layouts.app')

@section('title', 'Dashboard Eksekutif')

@section('content')
<div class="space-y-6">

    <!-- HERO HEADER -->
    <div class="bg-gradient-to-r from-emerald-800 via-teal-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <!-- Background decorative pattern -->
        <div class="absolute -right-10 -bottom-10 opacity-10 text-9xl">
            <i class="bi bi-bank2"></i>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="inline-flex items-center space-x-2 bg-emerald-500/20 text-emerald-300 text-xs px-3 py-1 rounded-full font-semibold mb-3 border border-emerald-400/30">
                    <i class="bi bi-shield-check"></i>
                    <span>Tahun Anggaran {{ date('Y') }} &bull; BUMDes Terdaftar Resmi</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Sistem Informasi Laporan Keuangan
                </h1>
                <p class="text-emerald-100/90 text-sm mt-1 max-w-2xl leading-relaxed">
                    Badan Usaha Milik Desa (BUMDes) <strong>Rakyat Mandiri</strong>. Mengelola pembukuan terintegrasi untuk unit CFD dan Pamsimas.
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <!-- <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('finance.transactions') }}?action=add_income"
                    class="inline-flex items-center space-x-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-lg shadow-emerald-950/40 transition">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>Kas Masuk</span>
                </a>
                <a href="{{ route('finance.transactions') }}?action=add_expense"
                    class="inline-flex items-center space-x-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-lg shadow-rose-950/40 transition">
                    <i class="bi bi-dash-circle-fill"></i>
                    <span>Kas Keluar</span>
                </a>
                <a href="{{ route('finance.reports') }}"
                    class="inline-flex items-center space-x-2 bg-white/10 hover:bg-white/20 text-white text-xs font-bold px-4 py-2.5 rounded-xl backdrop-blur-md border border-white/20 transition">
                    <i class="bi bi-printer"></i>
                    <span>Cetak Laporan</span>
                </a>
            </div> -->
        </div>
    </div>

    <!-- 4 KARTU METRIK KONSOLIDASI KEUANGAN UTAMA -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Saldo Kas Riil -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:border-emerald-500 transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Saldo Kas Terpadu</span>
                <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                    <i class="bi bi-wallet2"></i>
                </span>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold text-slate-800">
                    Rp {{ number_format($netProfit, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-slate-500 mt-1 flex items-center space-x-1">
                    <span class="text-emerald-600 font-semibold"><i class="bi bi-check-circle-fill"></i> Riil BUMDes</span>
                    <span>(Kas Tunai & Bank)</span>
                </p>
            </div>
        </div>

        <!-- 2. Total Kas Masuk -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:border-teal-500 transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Kas Masuk</span>
                <span class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-lg">
                    <i class="bi bi-arrow-down-left-circle-fill"></i>
                </span>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold text-teal-700">
                    Rp {{ number_format($totalIncome, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-slate-500 mt-1">
                    Pendapatan Usaha & Modal Masuk
                </p>
            </div>
        </div>

        <!-- 3. Total Kas Keluar -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:border-rose-500 transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Kas Keluar</span>
                <span class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                </span>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold text-rose-600">
                    Rp {{ number_format($totalExpense, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-slate-500 mt-1">
                    Beban Operasional & Pengadaan
                </p>
            </div>
        </div>

        <!-- 4. Laba Bersih Usaha -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:border-amber-500 transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Laba Bersih Konsolidasi</span>
                <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                    <i class="bi bi-graph-up-arrow"></i>
                </span>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold text-amber-600">
                    Rp {{ number_format($netProfit, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-slate-500 mt-1">
                    Ringkasan kas masuk dan keluar
                </p>
            </div>
        </div>
    </div>

    <!-- KARTU UNIT USAHA DETAIL -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <div>
                <h2 class="text-base font-bold text-slate-800">Performa Unit Usaha BUMDes Rakyat Mandiri</h2>
                <p class="text-xs text-slate-500">Ringkasan operasional dan keuangan masing-masing divisi bisnis desa</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- UNIT CFD & UMKM -->
            @php $cfd = $units->firstWhere('code', 'cfd'); @endphp
            <div class="bg-white rounded-2xl p-5 border border-slate-200 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="w-9 h-9 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-lg font-bold">
                            <i class="bi bi-shop"></i>
                        </span>
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200">
                            Unit 3
                        </span>
                    </div>
                    <h3 class="font-bold text-slate-800 text-base">CFD & Pasar UMKM</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Retribusi Lapak, Parkir & Sampah</p>

                    <div class="my-4 space-y-1.5 pt-3 border-t border-slate-100 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Pendapatan:</span>
                            <span class="font-bold text-slate-800">Rp {{ number_format($cfd['income'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Biaya Kebersihan:</span>
                            <span class="font-bold text-rose-600">Rp {{ number_format($cfd['expense'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-bold pt-1 border-t border-slate-100">
                            <span class="text-rose-800">Laba Unit:</span>
                            <span class="text-rose-700">Rp {{ number_format($cfd['net_profit'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="bg-rose-50/70 p-2.5 rounded-xl text-[11px] text-rose-900 space-y-1">
                        <div class="flex justify-between">
                            <span>Pedagang Terdaftar:</span>
                            <span class="font-bold">{{ $totalCfdVendors }} UMKM</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Event Terakhir:</span>
                            <span class="font-bold">{{ $latestCfd ? $latestCfd->event_code : 'Pekan 42' }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100">
                    <a href="{{ route('cfd.index') }}" class="text-xs font-bold text-rose-700 hover:text-rose-900 flex items-center justify-between">
                        <span>Buka Modul CFD</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- UNIT PAMSIMAS -->
            @php $pam = $units->firstWhere('code', 'pamsimas'); @endphp
            <div class="bg-white rounded-2xl p-5 border border-slate-200 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="w-9 h-9 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center text-lg font-bold">
                            <i class="bi bi-droplet-half"></i>
                        </span>
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded bg-sky-50 text-sky-700 border border-sky-200">
                            Unit 4
                        </span>
                    </div>
                    <h3 class="font-bold text-slate-800 text-base">Pamsimas Tirta Mandiri</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Air Bersih Warga & Meteran</p>

                    <div class="my-4 space-y-1.5 pt-3 border-t border-slate-100 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Pendapatan Air:</span>
                            <span class="font-bold text-slate-800">Rp {{ number_format($pam['income'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-bold pt-1 border-t border-slate-100">
                            <span class="text-sky-800">Laba/Defisit:</span>
                            <span class="{{ ($pam['net_profit'] ?? 0) >= 0 ? 'text-sky-700' : 'text-rose-600' }}">
                                Rp {{ number_format($pam['net_profit'] ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-sky-50/70 p-2.5 rounded-xl text-[11px] text-sky-900 space-y-1">
                        <div class="flex justify-between">
                            <span>Pelanggan Warga:</span>
                            <span class="font-bold">{{ $activePamsimasCustomers }} Sambungan</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tagihan Belum Lunas:</span>
                            <span class="font-bold text-amber-700">{{ $unpaidWaterBills }} Warga (Rp {{ number_format($totalUnpaidAmount, 0, ',', '.') }})</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100">
                    <a href="{{ route('pamsimas.index') }}" class="text-xs font-bold text-sky-700 hover:text-sky-900 flex items-center justify-between">
                        <span>Buka Modul Pamsimas</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- GRAFIK ARUS KAS & KOMPOSISI PENDAPATAN -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Monthly Inflow vs Outflow Bar Chart (8 cols) -->
        <div class="lg:col-span-8 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Tren Arus Kas Bulanan (Tahun {{ date('Y') }})</h3>
                    <p class="text-xs text-slate-500">Perbandingan total kas masuk vs pengeluaran operasional per bulan</p>
                </div>
                <div class="flex items-center space-x-3 text-xs">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span class="text-slate-600">Kas Masuk</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                        <span class="text-slate-600">Kas Keluar</span>
                    </div>
                </div>
            </div>

            <div class="h-64">
                <canvas id="monthlyCashflowChart"></canvas>
            </div>
        </div>

        <!-- Revenue Distribution Doughnut Chart -->
        <div class="lg:col-span-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Distribusi Pendapatan per Unit</h3>
                <p class="text-xs text-slate-500 mb-4">Kontribusi pendapatan terhadap BUMDes</p>
                <div class="h-52 relative flex items-center justify-center">
                    <canvas id="unitDistributionChart"></canvas>
                </div>
            </div>

            <!-- Legend summary -->
            <div class="grid grid-cols-2 gap-2 pt-4 border-t border-slate-100 text-xs">
                <div class="flex items-center space-x-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    <span class="text-slate-600">CFD UMKM</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
                    <span class="text-slate-600">Pamsimas</span>
                </div>
            </div>
        </div>
    </div>

    <!-- TRANSAKSI TERAKHIR BUKU KAS -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Transaksi Kas Umum Terakhir</h3>
                <p class="text-xs text-slate-500">Aktivitas penerimaan dan pengeluaran kas BUMDes</p>
            </div>
            <a href="{{ route('finance.transactions') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-900 inline-flex items-center space-x-1">
                <span>Lihat Seluruh Buku Kas</span>
                <i class="bi bi-chevron-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">No Bukti</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Unit Usaha</th>
                        <th class="py-3 px-4">Keterangan Transaksi</th>
                        <th class="py-3 px-4">Penerima / Pembayar</th>
                        <th class="py-3 px-4 text-right">Nominal</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentTransactions as $tx)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4 font-bold text-slate-800 whitespace-nowrap">
                            {{ $tx->transaction_code }}
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap text-slate-600">
                            {{ $tx->transaction_date->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase
                                    {{ $tx->unit_code === 'cfd' ? 'bg-rose-100 text-rose-800' :
                                      ($tx->unit_code === 'pamsimas' ? 'bg-sky-100 text-sky-800' : 'bg-slate-100 text-slate-800') }}">
                                {{ $tx->unit_code }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-slate-700 max-w-xs truncate" title="{{ $tx->description }}">
                            {{ $tx->description }}
                        </td>
                        <td class="py-3 px-4 text-slate-600 whitespace-nowrap">
                            {{ $tx->recipient_or_payer ?: '-' }}
                        </td>
                        <td class="py-3 px-4 text-right font-extrabold whitespace-nowrap {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $tx->type === 'income' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-center whitespace-nowrap">
                            <a href="{{ route('finance.transactions.print', $tx->id) }}" target="_blank"
                                class="text-slate-400 hover:text-emerald-600 p-1 rounded" title="Cetak Kwitansi">
                                <i class="bi bi-printer text-sm"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">
                            Belum ada transaksi kas yang tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Chart Arus Kas Bulanan
        const monthlyData = @json($monthlyData);
        const labels = monthlyData.map(d => d.month);
        const incomeData = monthlyData.map(d => d.income);
        const expenseData = monthlyData.map(d => d.expense);

        const ctxMonthly = document.getElementById('monthlyCashflowChart').getContext('2d');
        new Chart(ctxMonthly, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Kas Masuk (Rp)',
                        data: incomeData,
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                    },
                    {
                        label: 'Kas Keluar (Rp)',
                        data: expenseData,
                        backgroundColor: '#f43f5e',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            }
                        }
                    }
                }
            }
        });

        // 2. Chart Distribusi Pendapatan per Unit
        const revData = @json($unitRevenueDistribution);
        const ctxUnit = document.getElementById('unitDistributionChart').getContext('2d');
        new Chart(ctxUnit, {
            type: 'doughnut',
            data: {
                labels: ['CFD', 'Pamsimas'],
                datasets: [{
                    data: [
                        revData.cfd || 0,
                        revData.pamsimas || 0
                    ],
                    backgroundColor: ['#e11d48', '#0284c7'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endpush