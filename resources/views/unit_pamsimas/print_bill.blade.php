<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Rekening Air {{ $usage->bill_number }} - Pamsimas BUMDes</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('logo-bumdes.jpeg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-6 flex flex-col items-center">

    <div class="no-print mb-4 flex items-center space-x-3">
        <button onclick="window.print()" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg text-xs font-bold shadow transition">
            Cetak Struk Rekening Air Ini
        </button>
        <button onclick="window.close()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg text-xs font-bold transition">
            Tutup Jendela
        </button>
    </div>

    <div class="bg-white max-w-md w-full p-6 rounded-2xl shadow-md border border-slate-300 print:shadow-none print:border print:p-5 text-xs">
        <!-- HEADER KOP PAMSIMAS -->
        <div class="border-b-2 border-slate-900 pb-3 text-center">
            <h1 class="text-sm font-black uppercase text-slate-900 leading-tight">
                PAMSIMAS "TIRTA MANDIRI"
            </h1>
            <p class="text-[11px] font-bold text-sky-800 uppercase">BUMDes Rakyat Mandiri</p>
            <p class="text-[10px] text-slate-500">Desa Rakyat Mandiri, Kec. Makmur, Kab. Sejahtera</p>
            <p class="text-[9px] text-slate-400 italic">Layanan Distribusi Air Bersih Perpipaan Masyarakat</p>
        </div>

        <!-- STRUK REKENING TITLE -->
        <div class="my-3 text-center">
            <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">
                BUKTI PEMBAYARAN REKENING AIR
            </h2>
            <p class="text-[10px] text-slate-500">
                Periode Pemakaian: <strong>Bulan {{ \Carbon\Carbon::createFromFormat('Y-m', $usage->billing_period)->locale('id')->isoFormat('MMMM Y') }}</strong>
            </p>
        </div>

        <!-- DATA PELANGGAN -->
        <div class="border border-slate-200 rounded-xl p-3 bg-slate-50/50 space-y-1.5 text-[11px]">
            <div class="flex justify-between">
                <span class="text-slate-500">No. Sambungan:</span>
                <span class="font-mono font-bold text-sky-900">{{ $usage->customer->customer_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Nama Pelanggan:</span>
                <span class="font-bold text-slate-900">{{ $usage->customer->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Alamat / Dusun:</span>
                <span class="text-slate-700">{{ $usage->customer->hamlet }} ({{ $usage->customer->rt_rw }})</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Kategori Tarif:</span>
                <span class="font-semibold text-slate-700 uppercase">{{ str_replace('_', ' ', $usage->customer->category) }}</span>
            </div>
        </div>

        <!-- METERAN & RINCIAN TAGIHAN -->
        <div class="my-3 border border-slate-200 rounded-xl overflow-hidden">
            <div class="bg-slate-100 p-2 font-bold text-[11px] text-slate-800 flex justify-between">
                <span>Pencatatan Stand Meter</span>
                <span>Volume Pemakaian</span>
            </div>
            <div class="p-3 text-[11px] space-y-1.5">
                <div class="flex justify-between">
                    <span class="text-slate-500">Stand Meter Bulan Ini:</span>
                    <span class="font-mono font-bold text-slate-800">{{ $usage->current_meter }} m³</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Stand Meter Bulan Lalu:</span>
                    <span class="font-mono text-slate-600">{{ $usage->previous_meter }} m³</span>
                </div>
                <div class="flex justify-between font-bold text-sky-800 pt-1 border-t border-slate-100">
                    <span>Jumlah Kubikasi Terpakai:</span>
                    <span>{{ $usage->usage_m3 }} m³</span>
                </div>
            </div>

            <!-- RINCIAN BIAYA -->
            <div class="bg-slate-50/70 p-3 text-[11px] space-y-1 border-t border-slate-200">
                <div class="flex justify-between text-slate-600">
                    <span>Biaya Air ({{ $usage->usage_m3 }} m³ &times; Rp {{ number_format($usage->rate_per_m3, 0, ',', '.') }}):</span>
                    <span>Rp {{ number_format($usage->water_charge, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Biaya Beban / Abonemen:</span>
                    <span>Rp {{ number_format($usage->subscription_fee, 0, ',', '.') }}</span>
                </div>
                @if($usage->late_fee > 0)
                    <div class="flex justify-between text-rose-600">
                        <span>Denda Keterlambatan:</span>
                        <span>Rp {{ number_format($usage->late_fee, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-black text-sm text-slate-900 pt-2 border-t border-slate-200">
                    <span>TOTAL PEMBAYARAN:</span>
                    <span class="text-emerald-700">Rp {{ number_format($usage->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- STATUS LUNAS -->
        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-300 text-center my-3">
            <span class="font-extrabold text-sm text-emerald-800 uppercase tracking-wider block">
                *** LUNAS ***
            </span>
            <p class="text-[10px] text-emerald-700 mt-0.5">
                No. Bukti Kwitansi: <strong>{{ $usage->receipt_number ?: ('KW-' . $usage->bill_number) }}</strong>
            </p>
            <p class="text-[9px] text-slate-500">
                Dibayarkan pada: {{ $usage->paid_at ? $usage->paid_at->format('d/m/Y H:i') : date('d/m/Y') }} &bull; Kasir: {{ $usage->collected_by ?: 'Loket Pamsimas' }}
            </p>
        </div>

        <!-- FOOTER HIMBAUAN -->
        <div class="text-center text-[10px] text-slate-400 space-y-0.5 border-t border-dashed border-slate-300 pt-3">
            <p class="italic">"Hematlah air untuk kelestarian sumber mata air desa kita"</p>
            <p>Pengaduan Pipa Bocor / Gangguan Air: <strong>0812-3456-7895</strong></p>
            <p class="text-[8px] text-slate-300">Struk ini adalah bukti pembayaran sah dari BUMDes Rakyat Mandiri.</p>
        </div>
    </div>

</body>
</html>
