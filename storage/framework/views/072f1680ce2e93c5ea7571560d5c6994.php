<?php $__env->startSection('title', 'Buku Kas Umum & Transaksi'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="{ showModal: false, txType: 'income' }">

    <!-- HEADER & ACTIONS -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                Buku Kas Umum (BKU) & Transaksi Finansial
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Pencatatan kas masuk (BKM) dan kas keluar (BKK) terpadu seluruh unit usaha BUMDes Rakyat Mandiri
            </p>
        </div>

        <div class="flex items-center space-x-2.5">
            <button @click="showModal = true; txType = 'income'" 
                    class="inline-flex items-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Catat Kas Masuk</span>
            </button>
            <button @click="showModal = true; txType = 'expense'" 
                    class="inline-flex items-center space-x-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition">
                <i class="bi bi-dash-circle-fill"></i>
                <span>Catat Kas Keluar</span>
            </button>
        </div>
    </div>

    <!-- SUMMARY CARDS FOR CURRENT FILTER -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-500 font-medium">Kas Masuk (Terfilter)</span>
                <p class="text-lg font-extrabold text-emerald-600 mt-1">Rp <?php echo e(number_format($totalIncome, 0, ',', '.')); ?></p>
            </div>
            <span class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="bi bi-arrow-down-left"></i>
            </span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-500 font-medium">Kas Keluar (Terfilter)</span>
                <p class="text-lg font-extrabold text-rose-600 mt-1">Rp <?php echo e(number_format($totalExpense, 0, ',', '.')); ?></p>
            </div>
            <span class="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                <i class="bi bi-arrow-up-right"></i>
            </span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-500 font-medium">Saldo Selisih Periode</span>
                <p class="text-lg font-extrabold <?php echo e($balance >= 0 ? 'text-slate-800' : 'text-rose-600'); ?> mt-1">
                    Rp <?php echo e(number_format($balance, 0, ',', '.')); ?>

                </p>
            </div>
            <span class="w-10 h-10 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xl">
                <i class="bi bi-calculator"></i>
            </span>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form action="<?php echo e(route('finance.transactions')); ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Pencarian</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                       placeholder="No Bukti / Keterangan..." 
                       class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <?php if(Auth::user()->role !== 'admin_unit'): ?>
                <!-- Unit Usaha -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Unit Usaha</label>
                    <select id="filter-unit-code" name="unit_code" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        <option value="">Semua Unit</option>
                        <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($u->code); ?>" <?php echo e(request('unit_code') === $u->code ? 'selected' : ''); ?>>
                                <?php echo e($u->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            <?php endif; ?>

            <!-- Jenis Transaksi -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Jenis Kas</label>
                <select name="type" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-emerald-500">
                    <option value="">Semua (Masuk & Keluar)</option>
                    <option value="income" <?php echo e(request('type') === 'income' ? 'selected' : ''); ?>>Kas Masuk (+)</option>
                    <option value="expense" <?php echo e(request('type') === 'expense' ? 'selected' : ''); ?>>Kas Keluar (-)</option>
                </select>
            </div>

            <!-- Tanggal Mulai -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>" 
                       class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold py-2 rounded-lg transition">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="<?php echo e(route('finance.transactions')); ?>" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- TRANSACTIONS TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">No Bukti Kas</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Unit Usaha</th>
                        <th class="py-3 px-4">Keterangan</th>
                        <th class="py-3 px-4">Pihak Terkait</th>
                        <th class="py-3 px-4">Metode</th>
                        <th class="py-3 px-4 text-right">Nominal</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-bold text-slate-800 whitespace-nowrap">
                                <?php echo e($tx->transaction_code); ?>

                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-slate-600">
                                <?php echo e($tx->transaction_date->format('d/m/Y')); ?>

                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    <?php echo e($tx->unit_code === 'pertanian' ? 'bg-emerald-100 text-emerald-800' : 
                                      ($tx->unit_code === 'peternakan' ? 'bg-amber-100 text-amber-800' : 
                                      ($tx->unit_code === 'cfd' ? 'bg-rose-100 text-rose-800' : 
                                      ($tx->unit_code === 'pamsimas' ? 'bg-sky-100 text-sky-800' : 'bg-slate-100 text-slate-800')))); ?>">
                                    <?php echo e($tx->unit_code); ?>

                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-700 max-w-xs truncate" title="<?php echo e($tx->description); ?>">
                                <?php echo e($tx->description); ?>

                            </td>
                            <td class="py-3 px-4 text-slate-600 whitespace-nowrap">
                                <?php echo e($tx->recipient_or_payer ?: '-'); ?>

                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-[11px] uppercase text-slate-500">
                                <?php echo e($tx->payment_method === 'transfer_bank' ? 'Transfer BRI' : 'Tunai'); ?>

                            </td>
                            <td class="py-3 px-4 text-right font-extrabold whitespace-nowrap <?php echo e($tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600'); ?>">
                                <?php echo e($tx->type === 'income' ? '+' : '-'); ?> Rp <?php echo e(number_format($tx->amount, 0, ',', '.')); ?>

                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="<?php echo e(route('finance.transactions.print', $tx->id)); ?>" target="_blank" 
                                       class="p-1.5 text-slate-500 hover:text-emerald-600 rounded-lg hover:bg-slate-100 transition" title="Cetak Bukti Kas">
                                        <i class="bi bi-printer text-sm"></i>
                                    </a>
                                    <form action="<?php echo e(route('finance.transactions.destroy', $tx->id)); ?>" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-slate-100 transition" title="Hapus">
                                            <i class="bi bi-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="py-10 text-center text-slate-400">
                                Tidak ada data transaksi yang sesuai filter.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            <?php echo e($transactions->links()); ?>

        </div>
    </div>

    <!-- MODAL INPUT TRANSAKSI (ALPINE.JS) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showModal = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-white"
                          :class="txType === 'income' ? 'bg-emerald-600' : 'bg-rose-600'">
                        <i :class="txType === 'income' ? 'bi bi-plus-lg' : 'bi bi-dash-lg'"></i>
                    </span>
                    <h3 class="font-bold text-slate-800" x-text="txType === 'income' ? 'Catat Kas Masuk (BKM)' : 'Catat Kas Keluar (BKK)'"></h3>
                </div>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <form action="<?php echo e(route('finance.transactions.store')); ?>" method="POST" class="space-y-4 mt-4 text-xs">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="type" :value="txType">

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Unit Usaha</label>
                        <select name="unit_code" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-emerald-500">
                            <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($u->code); ?>"><?php echo e($u->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Transaksi</label>
                        <input type="date" name="transaction_date" value="<?php echo e(date('Y-m-d')); ?>" required
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-emerald-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nominal (Rp)</label>
                        <input type="number" name="amount" required min="1" placeholder="Contoh: 500000"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-emerald-500 font-bold text-slate-800">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Metode Pembayaran</label>
                        <select name="payment_method" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-emerald-500">
                            <option value="tunai">Kas Tunai</option>
                            <option value="transfer_bank">Transfer Bank BRI</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1" x-text="txType === 'income' ? 'Diterima Dari' : 'Diserahkan Kepada'"></label>
                    <input type="text" name="recipient_or_payer" placeholder="Nama Warga / Pedagang / Rekanan..."
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Keterangan Rinci Transaksi</label>
                    <textarea name="description" rows="2" required placeholder="Jelaskan peruntukan transaksi ini..."
                              class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-emerald-500"></textarea>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-bold">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-5 py-2 text-white rounded-lg font-bold shadow-sm"
                            :class="txType === 'income' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-rose-600 hover:bg-rose-700'">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\BumdesAntigravity\resources\views/finance/transactions.blade.php ENDPATH**/ ?>