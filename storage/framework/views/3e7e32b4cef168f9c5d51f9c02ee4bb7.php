<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karcis Lapak <?php echo e($ticket->ticket_number); ?> - CFD BUMDes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
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
        <button onclick="window.print()" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold shadow transition">
            Cetak Karcis Ini
        </button>
        <button onclick="window.close()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg text-xs font-bold transition">
            Tutup Jendela
        </button>
    </div>

    <div class="bg-white max-w-xs w-full p-6 rounded-2xl shadow-md border border-dashed border-slate-300 text-center text-xs print:shadow-none print:border print:p-4">
        <!-- HEADER -->
        <h1 class="text-sm font-extrabold uppercase tracking-tight text-slate-900">
            KARCIS RETRIBUSI LAPAK CFD
        </h1>
        <p class="text-[11px] font-bold text-rose-700 uppercase">BUMDes Rakyat Mandiri</p>
        <p class="text-[10px] text-slate-400 mt-0.5"><?php echo e($ticket->event->name ?? 'CFD Desa'); ?></p>

        <div class="border-t border-dashed border-slate-300 my-3"></div>

        <div class="text-left space-y-1.5 text-[11px]">
            <div class="flex justify-between">
                <span class="text-slate-500">No. Karcis:</span>
                <span class="font-mono font-bold text-slate-800"><?php echo e($ticket->ticket_number); ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Tanggal:</span>
                <span class="font-medium text-slate-700"><?php echo e($ticket->event->event_date->format('d/m/Y')); ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Nama Usaha:</span>
                <span class="font-bold text-slate-800 truncate max-w-[150px]"><?php echo e($ticket->vendor ? $ticket->vendor->name : ($ticket->vendor_name_manual ?: 'Pedagang')); ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Nomor Lapak:</span>
                <span class="font-extrabold text-rose-700"><?php echo e($ticket->spot_number); ?> (<?php echo e($ticket->zone->code ?? ''); ?>)</span>
            </div>
        </div>

        <div class="border-t border-dashed border-slate-300 my-3"></div>

        <div class="text-left space-y-1 text-[11px]">
            <div class="flex justify-between text-slate-600">
                <span>Retribusi Tempat:</span>
                <span>Rp <?php echo e(number_format($ticket->fee_amount, 0, ',', '.')); ?></span>
            </div>
            <?php if($ticket->electricity_fee > 0): ?>
                <div class="flex justify-between text-slate-600">
                    <span>Biaya Listrik:</span>
                    <span>Rp <?php echo e(number_format($ticket->electricity_fee, 0, ',', '.')); ?></span>
                </div>
            <?php endif; ?>
            <?php if($ticket->cleanliness_fee > 0): ?>
                <div class="flex justify-between text-slate-600">
                    <span>Biaya Kebersihan:</span>
                    <span>Rp <?php echo e(number_format($ticket->cleanliness_fee, 0, ',', '.')); ?></span>
                </div>
            <?php endif; ?>
            <div class="flex justify-between font-extrabold text-slate-900 pt-1.5 border-t border-slate-200 text-xs">
                <span>TOTAL BAYAR:</span>
                <span class="text-rose-700">Rp <?php echo e(number_format($ticket->total_amount, 0, ',', '.')); ?></span>
            </div>
        </div>

        <div class="border-t border-dashed border-slate-300 my-3"></div>

        <div class="text-[10px] text-slate-400 space-y-0.5">
            <p>Petugas: <?php echo e($ticket->collected_by ?: 'Pengelola CFD'); ?></p>
            <p class="font-bold text-slate-600">LUNAS &bull; TERIMA KASIH</p>
            <p class="italic text-[9px]">Jagalah kebersihan bersama di area CFD</p>
        </div>
    </div>

</body>
</html>
<?php /**PATH D:\BumdesAntigravity\resources\views/unit_cfd/print_ticket.blade.php ENDPATH**/ ?>