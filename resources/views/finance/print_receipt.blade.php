<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Kas {{ $transaction->transaction_code }} - BUMDes Rakyat Mandiri</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('logo-bumdes.jpeg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-6 flex flex-col items-center">

    <div class="no-print mb-4 flex items-center space-x-3">
        <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow transition">
            Cetak Bukti Kas Ini
        </button>
        <button onclick="window.close()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg text-xs font-bold transition">
            Tutup Jendela
        </button>
    </div>

    <div class="bg-white max-w-2xl w-full p-8 rounded-2xl shadow-md border border-slate-300 print:shadow-none print:border-none print:p-0">
        <!-- KOP BUMDES -->
        <div class="border-b-2 border-slate-900 pb-3 text-center">
            <h1 class="text-base font-extrabold uppercase text-slate-900">
                BADAN USAHA MILIK DESA (BUMDES) "RAKYAT MANDIRI"
            </h1>
            <p class="text-xs text-slate-600">Desa Cintarakyat, Kecamatan Samarang, Kabupaten Garut</p>
            <p class="text-[10px] text-slate-400">SK Desa No. 141/04/Desa/2022 &bull; Unit: {{ strtoupper($transaction->unit_code) }}</p>
        </div>

        <!-- TITLE BUKTI -->
        <div class="my-4 flex items-center justify-between text-xs">
            <div>
                <span class="inline-block px-3 py-1 font-extrabold uppercase rounded border
                    {{ $transaction->type === 'income' ? 'bg-emerald-50 text-emerald-800 border-emerald-300' : 'bg-rose-50 text-rose-800 border-rose-300' }}">
                    {{ $transaction->type === 'income' ? 'BUKTI KAS MASUK (BKM)' : 'BUKTI KAS KELUAR (BKK)' }}
                </span>
            </div>
            <div class="text-right">
                <p class="font-mono font-bold text-slate-800">{{ $transaction->transaction_code }}</p>
                <p class="text-[11px] text-slate-500">Tanggal: {{ $transaction->transaction_date->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- DETAILS -->
        <div class="border border-slate-200 rounded-xl p-4 my-4 space-y-2.5 text-xs">
            <div class="grid grid-cols-3">
                <span class="text-slate-500">{{ $transaction->type === 'income' ? 'Telah Diterima Dari' : 'Dibayarkan Kepada' }}:</span>
                <span class="col-span-2 font-bold text-slate-800">{{ $transaction->recipient_or_payer ?: '-' }}</span>
            </div>
            <div class="grid grid-cols-3">
                <span class="text-slate-500">Untuk Keperluan:</span>
                <span class="col-span-2 text-slate-800 leading-relaxed">{{ $transaction->description }}</span>
            </div>
            <div class="grid grid-cols-3">
                <span class="text-slate-500">Metode Pembayaran:</span>
                <span class="col-span-2 uppercase font-semibold text-slate-700">
                    {{ $transaction->payment_method === 'transfer_bank' ? 'Transfer Bank BRI' : 'Kas Tunai' }}
                </span>
            </div>
            <div class="grid grid-cols-3 pt-2 border-t border-slate-200">
                <span class="text-slate-700 font-bold">Jumlah Pembayaran:</span>
                <span class="col-span-2 text-base font-extrabold text-slate-900">
                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- SIGNATURES -->
        <div class="grid grid-cols-2 text-center text-xs pt-6 mt-6 border-t border-slate-200">
            <div>
                <p class="text-slate-500">Mengetahui,</p>
                <p class="font-bold text-slate-800">Ketua BUMDes</p>
                <div class="h-16"></div>
                <p class="font-bold underline text-slate-900">Dede Sandi</p>
            </div>
            <div>
                <p class="text-slate-500">Penerima / Penyetor,</p>
                <p class="font-bold text-slate-800">Pihak Terkait</p>
                <div class="h-16"></div>
                <p class="font-bold underline text-slate-900">{{ $transaction->recipient_or_payer ?: '....................' }}</p>
            </div>
        </div>
    </div>

</body>
</html>
