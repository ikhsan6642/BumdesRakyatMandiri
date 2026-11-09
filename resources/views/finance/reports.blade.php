@extends('layouts.app')

@section('title', 'Laporan Keuangan Resmi BUMDes')

@section('content')
@php
    $showWeekFilter = $unitCode !== 'pamsimas';
    $filterColumnCount = (Auth::user()->role === 'admin_unit' ? 3 : 4) + ($showWeekFilter ? 1 : 0);
@endphp
<div class="space-y-6">

    <!-- HEADER & PRINT CONTROLS -->
    <div class="no-print flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                Laporan Keuangan Resmi BUMDes "Rakyat Mandiri"
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Laporan Laba/Rugi Konsolidasi BUMDes
            </p>
        </div>

        <div class="flex items-center space-x-2.5">
            <button onclick="window.print()" 
                    class="inline-flex items-center space-x-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md transition">
                <i class="bi bi-printer-fill"></i>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- FILTER BAR (NO-PRINT) -->
    <div class="no-print bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('finance.reports') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-{{ $filterColumnCount }} gap-3 text-xs">
            <div>
                <label class="block font-bold text-slate-600 mb-1">Tahun Anggaran</label>
                <select name="year" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-600 mb-1">Bulan (Opsional)</label>
                <select name="month" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    <option value="">Sepanjang Tahun (Tahunan)</option>
                    @foreach([1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'] as $num => $nama)
                        <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>Bulan {{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            @if($showWeekFilter)
                <div>
                    <label for="report-week" class="block font-bold text-slate-600 mb-1">Minggu (Opsional)</label>
                    <input id="report-week" type="week" name="week" value="{{ $selectedWeek }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                </div>
            @endif

            @if(Auth::user()->role !== 'admin_unit')
                <div>
                    <label class="block font-bold text-slate-600 mb-1">Divisi / Unit Usaha</label>
                    <select name="unit_code" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                        <option value="all" {{ $unitCode === 'all' ? 'selected' : '' }}>Semua Unit (Konsolidasi)</option>
                        @foreach($units->whereIn('code', ['cfd', 'pamsimas']) as $u)
                            <option value="{{ $u->code }}" {{ $unitCode === $u->code ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex items-end">
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-lg transition">
                    <i class="bi bi-funnel"></i> Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- PRINT REPORT CONTAINER (WORKS AS REGULAR CARD & PRINT SHEET) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-10 print:border-none print:shadow-none print:p-0">

        <!-- KOP RESMI LAPORAN KEUANGAN BUMDES -->
        <div class="border-b-2 border-slate-900 pb-4 mb-6 text-center">
            <h2 class="text-lg font-black tracking-wider uppercase text-slate-900">
                BADAN USAHA MILIK DESA (BUMDES) "RAKYAT MANDIRI"
            </h2>
            <p class="text-xs font-medium text-slate-600">
                PEMERINTAH DESA CINTARAKYAT &bull; KECAMATAN SAMARANG &bull; KABUPATEN GARUT
            </p>
            <p class="text-[11px] text-slate-500 italic mt-0.5">
                SK Pendirian BUMDes No. 141/04/Desa/2022 &bull; Email: bumdes@rakyatmandiri.desa.id &bull; Telp: 0812-3456-7890
            </p>
        </div>

        <!-- 1. TAB LABA RUGI -->
        <div class="space-y-6">
            <div class="text-center mb-6">
                <h3 class="text-base font-extrabold text-slate-900 uppercase">
                    LAPORAN LABA / RUGI {{ $unitCode === 'all' ? 'KONSOLIDASI' : strtoupper($units->firstWhere('code', $unitCode)?->name ?? $unitCode) }} (INCOME STATEMENT)
                </h3>
                <p class="text-xs text-slate-600">
                    Periode:
                    @if($weekStart && $weekEnd)
                        Minggu ke-{{ $weekStart->format('W') }} ({{ $weekStart->translatedFormat('d M Y') }} - {{ $weekEnd->translatedFormat('d M Y') }})
                    @else
                        {{ $month ? 'Bulan ' . $month . ' ' : '' }}Tahun Anggaran {{ $year }}
                    @endif
                </p>
            </div>

            <!-- TABEL PENDAPATAN -->
            <div class="space-y-2">
                <div class="bg-slate-100 px-3 py-1.5 font-bold text-xs text-slate-800 uppercase flex justify-between">
                    <span>A. PENDAPATAN USAHA (REVENUE)</span>
                    <span>NOMINAL (RP)</span>
                </div>
                <table class="w-full text-xs">
                    <tbody class="divide-y divide-slate-100">
                        @forelse($revenueTransactions as $rev)
                            <tr>
                                <td colspan="2" class="py-2 px-3 text-slate-800 font-medium">
                                    {{ $rev->unit_name }}
                                </td>
                                <td class="py-2 px-3 text-right font-semibold text-slate-800">
                                    Rp {{ number_format($rev->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-3 px-3 text-center text-slate-400">Belum ada pendapatan tercatat.</td>
                            </tr>
                        @endforelse
                        <tr class="bg-emerald-50/60 font-bold border-t-2 border-slate-300">
                            <td colspan="2" class="py-2.5 px-3 text-emerald-900 uppercase">TOTAL PENDAPATAN USAHA (A)</td>
                            <td class="py-2.5 px-3 text-right text-emerald-800 text-sm">
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- TABEL BEBAN OPERASIONAL -->
            <div class="space-y-2 pt-4">
                <div class="bg-slate-100 px-3 py-1.5 font-bold text-xs text-slate-800 uppercase flex justify-between">
                    <span>B. BEBAN & BIAYA OPERASIONAL (EXPENSES)</span>
                    <span>NOMINAL (RP)</span>
                </div>
                <table class="w-full text-xs">
                    <tbody class="divide-y divide-slate-100">
                        @forelse($expenseTransactions as $exp)
                            <tr>
                                <td colspan="2" class="py-2 px-3 text-slate-800 font-medium">
                                    {{ $exp->unit_name }}
                                </td>
                                <td class="py-2 px-3 text-right font-semibold text-rose-700">
                                    Rp {{ number_format($exp->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-3 px-3 text-center text-slate-400">Belum ada pengeluaran tercatat.</td>
                            </tr>
                        @endforelse
                        <tr class="bg-rose-50/60 font-bold border-t-2 border-slate-300">
                            <td colspan="2" class="py-2.5 px-3 text-rose-900 uppercase">TOTAL BEBAN OPERASIONAL (B)</td>
                            <td class="py-2.5 px-3 text-right text-rose-800 text-sm">
                                Rp {{ number_format($totalExpense, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- HASIL LABA BERSIH -->
            <div class="pt-4 border-t-2 border-slate-800">
                <div class="p-4 rounded-xl flex items-center justify-between {{ $netProfit >= 0 ? 'bg-emerald-100 text-emerald-950' : 'bg-rose-100 text-rose-950' }}">
                    <div>
                        <h4 class="font-extrabold text-sm uppercase">
                            {{ $netProfit >= 0 ? 'LABA BERSIH TAHUN BERJALAN (A - B)' : 'RUGI BERSIH TAHUN BERJALAN (A - B)' }}
                        </h4>
                        <p class="text-xs opacity-80">Surplus/Defisit Keuangan BUMDes "Rakyat Mandiri"</p>
                    </div>
                    <div class="text-xl sm:text-2xl font-black">
                        Rp {{ number_format($netProfit, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- TANDA TANGAN PENGESAHAN LAPORAN (PRINT & CARD) -->
        <div class="pt-12 mt-10 border-t border-slate-300">
            <div class="flex justify-end text-center text-xs">
                <div class="w-1/2">
                    <p class="text-slate-500">Mengetahui,</p>
                    <p class="font-bold text-slate-800 mt-0.5">Ketua BUMDes "Rakyat Mandiri"</p>
                    <div class="h-20"></div>
                    <p class="font-bold underline text-slate-900">Dede Sandi</p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
