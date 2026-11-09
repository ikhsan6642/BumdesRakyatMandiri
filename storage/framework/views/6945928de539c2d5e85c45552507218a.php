<?php $__env->startSection('title', 'Unit 2: Peternakan Terpadu'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="{ 
    currentTab: '<?php echo e($tab); ?>', 
    showBatchModal: false, 
    showOpModal: false, 
    showSaleModal: false, 
    showPopModal: false,
    selectedBatchId: null
}">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-sm">
                    <i class="bi bi-bug-fill"></i>
                </span>
                <span class="text-xs uppercase font-extrabold text-amber-700 tracking-wider">Unit Usaha 2</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                Peternakan Terpadu BUMDes Rakyat Mandiri
            </h1>
            <p class="text-xs text-slate-500">
                Penggemukan sapi potong, budidaya kambing unggulan, pakan konsentrat, dan pupuk organik kandang (kohe)
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button @click="showBatchModal = true" class="px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-sm">
                <i class="bi bi-plus-circle"></i>
                <span>Batch Ternak Baru</span>
            </button>
            <button @click="showOpModal = true" class="px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-sm">
                <i class="bi bi-bag-plus"></i>
                <span>Catat Pakan & Vaksin</span>
            </button>
            <button @click="showSaleModal = true" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-sm">
                <i class="bi bi-cart-check"></i>
                <span>Penjualan Ternak / Hasil</span>
            </button>
        </div>
    </div>

    <!-- 4 KPI CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold uppercase">Populasi Ternak Hidup</span>
            <h3 class="text-xl font-extrabold text-slate-800 mt-1"><?php echo e($totalAnimals); ?> Ekor</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Sapi potong & kambing di kandang</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold uppercase">Total Penjualan Ternak</span>
            <h3 class="text-xl font-extrabold text-emerald-700 mt-1">Rp <?php echo e(number_format($totalSales, 0, ',', '.')); ?></h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Sapi qurban, kambing & pupuk kohe</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold uppercase">Biaya Pakan & Vaksin</span>
            <h3 class="text-xl font-extrabold text-rose-600 mt-1">Rp <?php echo e(number_format($totalFeedAndMed, 0, ',', '.')); ?></h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Konsentrat, hijauan & perawatan</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold uppercase">Laba Bersih Peternakan</span>
            <h3 class="text-xl font-extrabold text-amber-700 mt-1">Rp <?php echo e(number_format($netProfit, 0, ',', '.')); ?></h3>
            <p class="text-[11px] text-emerald-600 font-semibold mt-0.5"><i class="bi bi-graph-up"></i> Surplus Divisi Ternak</p>
        </div>
    </div>

    <!-- TABS -->
    <div class="flex border-b border-slate-200 space-x-6 text-xs font-bold">
        <button @click="currentTab = 'batches'" 
                :class="currentTab === 'batches' ? 'border-b-2 border-amber-600 text-amber-700 pb-2.5' : 'text-slate-500 hover:text-slate-700 pb-2.5'">
            <i class="bi bi-grid mr-1"></i> Kelompok / Batch Ternak
        </button>
        <button @click="currentTab = 'operations'" 
                :class="currentTab === 'operations' ? 'border-b-2 border-amber-600 text-amber-700 pb-2.5' : 'text-slate-500 hover:text-slate-700 pb-2.5'">
            <i class="bi bi-capsule mr-1"></i> Biaya Pakan & Obat
        </button>
        <button @click="currentTab = 'sales'" 
                :class="currentTab === 'sales' ? 'border-b-2 border-amber-600 text-amber-700 pb-2.5' : 'text-slate-500 hover:text-slate-700 pb-2.5'">
            <i class="bi bi-receipt mr-1"></i> Penjualan Ternak & Kohe
        </button>
    </div>

    <!-- TAB 1: BATCHES -->
    <div x-show="currentTab === 'batches'" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php $__empty_1 = true; $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono text-xs font-extrabold text-slate-800"><?php echo e($batch->code); ?></span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?php echo e($batch->status === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700'); ?>">
                                <?php echo e($batch->status); ?>

                            </span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-base"><?php echo e($batch->name); ?></h3>
                        <p class="text-xs text-slate-500 mt-0.5"><i class="bi bi-geo-alt"></i> <?php echo e($batch->cage_location); ?> &bull; <?php echo e($batch->category->name ?? ''); ?></p>

                        <!-- Populasi Meter -->
                        <div class="my-3 p-3 bg-amber-50/60 rounded-xl border border-amber-200/60 text-xs">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-amber-900">Populasi Saat Ini:</span>
                                <span class="text-base font-extrabold text-amber-800"><?php echo e($batch->current_quantity); ?> Ekor</span>
                            </div>
                            <div class="grid grid-cols-3 gap-1 text-[11px] text-slate-600 pt-1 border-t border-amber-200/50">
                                <span>Awal: <?php echo e($batch->initial_quantity); ?></span>
                                <span>Lahir: +<?php echo e($batch->birth_count); ?></span>
                                <span>Terjual: <?php echo e($batch->sold_count); ?></span>
                            </div>
                        </div>

                        <!-- Finansial Batch -->
                        <div class="space-y-1 text-xs text-slate-600">
                            <div class="flex justify-between">
                                <span>Modal Beli Bibit:</span>
                                <span class="font-semibold text-slate-800">Rp <?php echo e(number_format($batch->initial_purchase_cost, 0, ',', '.')); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Pakan & Obat:</span>
                                <span class="font-semibold text-rose-600">Rp <?php echo e(number_format($batch->operations->sum('total_cost'), 0, ',', '.')); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Hasil Penjualan:</span>
                                <span class="font-semibold text-emerald-700">Rp <?php echo e(number_format($batch->sales->sum('total_amount'), 0, ',', '.')); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-[11px] text-slate-400">Masuk: <?php echo e($batch->entry_date->format('d/m/Y')); ?></span>
                        <button @click="selectedBatchId = <?php echo e($batch->id); ?>; showPopModal = true" 
                                class="text-amber-700 hover:text-amber-900 font-bold flex items-center space-x-1">
                            <span>Catat Mutasi</span>
                            <i class="bi bi-arrow-left-right"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-3 text-center py-10 bg-white rounded-2xl border text-slate-400 text-xs">
                    Belum ada kelompok ternak yang didaftarkan.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- TAB 2: OPERASIONAL PAKAN & OBAT -->
    <div x-show="currentTab === 'operations'" class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-sm">Riwayat Pembelian Pakan, Vitamin & Vaksin Ternak</h3>
                <button @click="showOpModal = true" class="px-3 py-1.5 bg-rose-600 text-white rounded-lg text-xs font-bold">
                    + Tambah Biaya Pakan/Vaksin
                </button>
            </div>
            <div class="overflow-x-auto text-xs">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 font-semibold uppercase border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Kelompok Ternak</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Nama Barang / Vaksin</th>
                            <th class="py-3 px-4">Jumlah</th>
                            <th class="py-3 px-4">Harga Satuan</th>
                            <th class="py-3 px-4 text-right">Total Biaya</th>
                            <th class="py-3 px-4">Supplier / Dokter</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $operations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-4 whitespace-nowrap"><?php echo e($op->date->format('d/m/Y')); ?></td>
                                <td class="py-3 px-4 font-bold text-slate-800"><?php echo e($op->batch->name ?? '-'); ?></td>
                                <td class="py-3 px-4 uppercase text-[10px] font-bold">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700"><?php echo e(str_replace('_', ' ', $op->category)); ?></span>
                                </td>
                                <td class="py-3 px-4 font-medium"><?php echo e($op->item_name); ?></td>
                                <td class="py-3 px-4"><?php echo e($op->quantity); ?> <?php echo e($op->unit); ?></td>
                                <td class="py-3 px-4">Rp <?php echo e(number_format($op->unit_price, 0, ',', '.')); ?></td>
                                <td class="py-3 px-4 text-right font-extrabold text-rose-600">Rp <?php echo e(number_format($op->total_cost, 0, ',', '.')); ?></td>
                                <td class="py-3 px-4 text-slate-500"><?php echo e($op->supplier_or_doctor ?: '-'); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="8" class="text-center py-6 text-slate-400">Belum ada biaya pakan/kesehatan dicatat.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                <?php echo e($operations->links()); ?>

            </div>
        </div>
    </div>

    <!-- TAB 3: PENJUALAN TERNAK & HASIL -->
    <div x-show="currentTab === 'sales'" class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-sm">Penjualan Hewan Ternak, Susu, Daging & Pupuk Kohe</h3>
                <button @click="showSaleModal = true" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-bold">
                    + Catat Penjualan Ternak/Kohe
                </button>
            </div>
            <div class="overflow-x-auto text-xs">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 font-semibold uppercase border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">No Faktur</th>
                            <th class="py-3 px-4">Tanggal Jual</th>
                            <th class="py-3 px-4">Jenis Penjualan</th>
                            <th class="py-3 px-4">Uraian Barang</th>
                            <th class="py-3 px-4">Volume</th>
                            <th class="py-3 px-4">Pembeli</th>
                            <th class="py-3 px-4 text-right">Total Penjualan</th>
                            <th class="py-3 px-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-4 font-mono font-bold text-slate-800"><?php echo e($s->invoice_number); ?></td>
                                <td class="py-3 px-4"><?php echo e($s->sale_date->format('d/m/Y')); ?></td>
                                <td class="py-3 px-4 uppercase text-[10px] font-bold">
                                    <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800"><?php echo e(str_replace('_', ' ', $s->sale_type)); ?></span>
                                </td>
                                <td class="py-3 px-4 font-medium text-slate-800"><?php echo e($s->item_description); ?></td>
                                <td class="py-3 px-4 font-bold"><?php echo e($s->quantity); ?> <?php echo e($s->unit); ?></td>
                                <td class="py-3 px-4 text-slate-700"><?php echo e($s->buyer_name); ?></td>
                                <td class="py-3 px-4 text-right font-extrabold text-emerald-700">Rp <?php echo e(number_format($s->total_amount, 0, ',', '.')); ?></td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?php echo e($s->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'); ?>">
                                        <?php echo e($s->payment_status); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="8" class="text-center py-6 text-slate-400">Belum ada penjualan ternak yang tercatat.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                <?php echo e($sales->links()); ?>

            </div>
        </div>
    </div>

    <!-- MODAL 1: BATCH BARU -->
    <div x-show="showBatchModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showBatchModal = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="font-bold text-slate-800 text-sm">Tambah Batch / Kelompok Ternak</h3>
                <button @click="showBatchModal = false" class="text-slate-400"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="<?php echo e(route('livestock.batches.store')); ?>" method="POST" class="space-y-3 mt-4 text-xs">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kategori Hewan Ternak</label>
                    <select name="category_id" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?> (<?php echo e(strtoupper($cat->type)); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Kelompok / Periode</label>
                    <input type="text" name="name" required placeholder="Penggemukan Sapi Limousin Periode II" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Lokasi Kandang</label>
                    <input type="text" name="cage_location" required placeholder="Kandang Komunal BUMDes Blok Barat" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jumlah Awal (Ekor)</label>
                        <input type="number" name="initial_quantity" required min="1" placeholder="10" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jantan</label>
                        <input type="number" name="male_count" required min="0" value="0" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Betina</label>
                        <input type="number" name="female_count" required min="0" value="0" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Biaya Beli Bibit (Rp)</label>
                        <input type="number" name="initial_purchase_cost" required placeholder="140000000" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Masuk Kandang</label>
                        <input type="date" name="entry_date" value="<?php echo e(date('Y-m-d')); ?>" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div class="flex justify-end space-x-2 pt-3 border-t">
                    <button type="button" @click="showBatchModal = false" class="px-4 py-2 bg-slate-100 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 text-white rounded-lg font-bold">Simpan Batch Ternak</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: CATAT PAKAN & OBAT -->
    <div x-show="showOpModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showOpModal = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="font-bold text-slate-800 text-sm">Catat Biaya Pakan & Perawatan Hewan</h3>
                <button @click="showOpModal = false" class="text-slate-400"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="<?php echo e(route('livestock.operations.store')); ?>" method="POST" class="space-y-3 mt-4 text-xs">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih Batch Ternak</label>
                    <select name="batch_id" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                        <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b->id); ?>">[<?php echo e($b->code); ?>] <?php echo e($b->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                        <select name="category" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                            <option value="pakan_konsentrat">Pakan Konsentrat</option>
                            <option value="hijauan">Pakan Rumput Hijauan</option>
                            <option value="vaksin_obat">Vaksin & Obat Cacing</option>
                            <option value="vitamin">Vitamin / Suplemen</option>
                            <option value="perawatan_kandang">Perawatan & Kebersihan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Biaya</label>
                        <input type="date" name="date" value="<?php echo e(date('Y-m-d')); ?>" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Barang / Uraian</label>
                    <input type="text" name="item_name" required placeholder="Konsentrat Penggemukan Sapi 20 Sak" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Volume</label>
                        <input type="number" step="0.01" name="quantity" required placeholder="20" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Satuan</label>
                        <input type="text" name="unit" required placeholder="sak/kg/dosis" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Harga Satuan (Rp)</label>
                        <input type="number" name="unit_price" required placeholder="185000" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Supplier / Dokter Hewan</label>
                    <input type="text" name="supplier_or_doctor" placeholder="PT Ternak Jaya / drh. Fauzi" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                </div>
                <div class="flex justify-end space-x-2 pt-3 border-t">
                    <button type="button" @click="showOpModal = false" class="px-4 py-2 bg-slate-100 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 text-white rounded-lg font-bold">Simpan ke Kas Keluar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: PENJUALAN TERNAK -->
    <div x-show="showSaleModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showSaleModal = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="font-bold text-slate-800 text-sm">Penjualan Ternak / Hasil Ternak</h3>
                <button @click="showSaleModal = false" class="text-slate-400"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="<?php echo e(route('livestock.sales.store')); ?>" method="POST" class="space-y-3 mt-4 text-xs">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih Batch Ternak</label>
                    <select name="batch_id" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                        <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b->id); ?>">[<?php echo e($b->code); ?>] <?php echo e($b->name); ?> (Sisa: <?php echo e($b->current_quantity); ?> Ekor)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jenis Penjualan</label>
                        <select name="sale_type" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                            <option value="hewan_hidup">Hewan Hidup (Sapi/Kambing)</option>
                            <option value="pupuk_kandang">Pupuk Organik Kohe</option>
                            <option value="susu">Susu Murni</option>
                            <option value="daging">Daging Potong</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Jual</label>
                        <input type="date" name="sale_date" value="<?php echo e(date('Y-m-d')); ?>" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Uraian Barang / Hewan</label>
                    <input type="text" name="item_description" required placeholder="Contoh: 2 Ekor Sapi Limousin Bobot 530kg" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Volume</label>
                        <input type="number" step="0.01" name="quantity" required placeholder="2" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Satuan</label>
                        <input type="text" name="unit" required placeholder="ekor/karung/liter" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Harga Satuan (Rp)</label>
                        <input type="number" name="unit_price" required placeholder="26500000" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Pembeli</label>
                        <input type="text" name="buyer_name" required placeholder="H. Abdullah" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">No HP</label>
                        <input type="text" name="buyer_phone" placeholder="08..." class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <input type="hidden" name="payment_status" value="lunas">
                <input type="hidden" name="payment_method" value="tunai">
                <div class="flex justify-end space-x-2 pt-3 border-t">
                    <button type="button" @click="showSaleModal = false" class="px-4 py-2 bg-slate-100 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-lg font-bold">Simpan ke Kas Masuk</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 4: MUTASI POPULASI -->
    <div x-show="showPopModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showPopModal = false" class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="font-bold text-slate-800 text-sm">Catat Mutasi Populasi Ternak</h3>
                <button @click="showPopModal = false" class="text-slate-400"><i class="bi bi-x-lg"></i></button>
            </div>
            <form :action="'<?php echo e(url('/unit/peternakan/populasi')); ?>/' + selectedBatchId" method="POST" class="space-y-3 mt-4 text-xs">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Jenis Mutasi</label>
                    <select name="event_type" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-bold">
                        <option value="kelahiran">Kelahiran Anakan (+ Tambah)</option>
                        <option value="kematian">Kematian Ternak (- Kurang)</option>
                        <option value="penyesuaian">Penyesuaian Total Ekor</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Jumlah Ekor</label>
                    <input type="number" name="quantity" required min="1" placeholder="1" class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-bold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Keterangan / Catatan</label>
                    <textarea name="notes" rows="2" placeholder="Kondisi anakan sehat, dll..." class="w-full px-3 py-2 bg-slate-50 border rounded-lg"></textarea>
                </div>
                <div class="flex justify-end space-x-2 pt-3 border-t">
                    <button type="button" @click="showPopModal = false" class="px-4 py-2 bg-slate-100 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 text-white rounded-lg font-bold">Simpan Mutasi</button>
                </div>
            </form>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\BumdesAntigravity\resources\views/unit_peternakan/index.blade.php ENDPATH**/ ?>