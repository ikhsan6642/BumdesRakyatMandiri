<?php $__env->startSection('title', 'Unit 4: Pamsimas Air Bersih'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="{ 
    currentTab: '<?php echo e($tab); ?>', 
    showReadingModal: false, 
    showCustModal: false, 
    showCustomerEditModal: false,
    showPayModal: false,
    selectedCustomer: null,
    selectedUsage: null,
    customersList: <?php echo \Illuminate\Support\Js::from($allCustomers)->toHtml() ?>,
    selectedCustId: '',
    prevMeter: 0,
    curMeter: 0,
    openCustomerEdit(customer) {
        this.selectedCustomer = customer;
        this.showCustomerEditModal = true;
    },
    updatePreviousMeter() {
        const found = this.customersList.find(c => c.id == this.selectedCustId);
        if (found) {
            this.prevMeter = found.last_meter_reading !== undefined ? found.last_meter_reading : (found.initial_meter || 0);
            this.curMeter = this.prevMeter;
        }
    }
}">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <span class="w-7 h-7 rounded-lg bg-sky-100 text-sky-800 flex items-center justify-center font-bold text-sm">
                    <i class="bi bi-droplet-half"></i>
                </span>
                <span class="text-xs uppercase font-extrabold text-sky-700 tracking-wider">Unit Usaha 4</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                Pamsimas Tirta Mandiri BUMDes
            </h1>
            <p class="text-xs text-slate-500">
                Pencatatan meteran air perpipaan warga, tagihan bulanan, pembayaran struk rekening air, dan operasional pompa
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button @click="showReadingModal = true" class="px-3.5 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-sm">
                <i class="bi bi-speedometer2"></i>
                <span>Catat Meteran Warga</span>
            </button>
            <button @click="showCustModal = true" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-sm">
                <i class="bi bi-person-plus-fill"></i>
                <span>Pasang Sambungan Baru</span>
            </button>
        </div>
    </div>

    <!-- 3 KPI CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold uppercase">Total Tagihan Periode <?php echo e($period); ?></span>
            <h3 class="text-xl font-extrabold text-slate-800 mt-1">Rp <?php echo e(number_format($totalBilled, 0, ',', '.')); ?></h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Pemakaian: <?php echo e(number_format($totalUsageM3, 0, ',', '.')); ?> m³ air</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold uppercase">Uang Air Terkumpul (Lunas)</span>
            <h3 class="text-xl font-extrabold text-emerald-700 mt-1">Rp <?php echo e(number_format($totalCollected, 0, ',', '.')); ?></h3>
            <p class="text-[11px] text-emerald-600 font-semibold mt-0.5"><i class="bi bi-check-circle"></i> Masuk ke Kas BUMDes</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold uppercase">Piutang Air (Belum Lunas)</span>
            <h3 class="text-xl font-extrabold text-amber-600 mt-1">Rp <?php echo e(number_format($totalUnpaid, 0, ',', '.')); ?></h3>
            <p class="text-[11px] text-amber-700 font-medium mt-0.5">Menunggu pembayaran warga</p>
        </div>

    </div>

    <!-- TABS NAVIGATION -->
    <div class="flex border-b border-slate-200 space-x-6 text-xs font-bold">
        <button @click="currentTab = 'bills'" 
                :class="currentTab === 'bills' ? 'border-b-2 border-sky-600 text-sky-700 pb-2.5' : 'text-slate-500 hover:text-slate-700 pb-2.5'">
            <i class="bi bi-receipt-cutoff mr-1"></i> Tagihan Rekening Air Bulanan
        </button>
        <button @click="currentTab = 'customers'" 
                :class="currentTab === 'customers' ? 'border-b-2 border-sky-600 text-sky-700 pb-2.5' : 'text-slate-500 hover:text-slate-700 pb-2.5'">
            <i class="bi bi-people mr-1"></i> Data Pelanggan Warga
        </button>
        <button @click="currentTab = 'tariffs'" 
                :class="currentTab === 'tariffs' ? 'border-b-2 border-sky-600 text-sky-700 pb-2.5' : 'text-slate-500 hover:text-slate-700 pb-2.5'">
            <i class="bi bi-sliders mr-1"></i> Pengaturan Tarif & Abonemen
        </button>
    </div>

    <!-- TAB 1: TAGIHAN REKENING AIR BULANAN -->
    <div x-show="currentTab === 'bills'" class="space-y-4">
        <!-- Filter Periode & Status -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <form action="<?php echo e(route('pamsimas.index')); ?>" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <input type="hidden" name="tab" value="bills">
                <div class="flex items-center space-x-2">
                    <span class="font-bold text-slate-700">Periode:</span>
                    <select name="period" onchange="this.form.submit()" class="px-3 py-1.5 bg-slate-50 border rounded-lg font-bold">
                        <?php $__currentLoopData = $availablePeriods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($ap); ?>" <?php echo e($period === $ap ? 'selected' : ''); ?>>
                                Bulan <?php echo e(\Carbon\Carbon::createFromFormat('Y-m', $ap)->locale('id')->isoFormat('MMMM Y')); ?> (<?php echo e($ap); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="font-bold text-slate-700">Status Bayar:</span>
                    <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 bg-slate-50 border rounded-lg">
                        <option value="">Semua</option>
                        <option value="belum_lunas" <?php echo e(request('status') === 'belum_lunas' ? 'selected' : ''); ?>>Belum Lunas</option>
                        <option value="lunas" <?php echo e(request('status') === 'lunas' ? 'selected' : ''); ?>>Lunas</option>
                    </select>
                </div>
            </form>

            <button @click="showReadingModal = true" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded-lg font-bold">
                + Input Stand Meter Bulan Ini
            </button>
        </div>

        <!-- Tabel Tagihan Air -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto text-xs">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 font-semibold uppercase border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">No Rekening</th>
                            <th class="py-3 px-4">Pelanggan Warga</th>
                            <th class="py-3 px-4">Dusun / RT</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4 text-center">Meter Lalu & Kini</th>
                            <th class="py-3 px-4 text-center">Volume (m³)</th>
                            <th class="py-3 px-4 text-right">Biaya Air</th>
                            <th class="py-3 px-4 text-right">Abonemen</th>
                            <th class="py-3 px-4 text-right">Total Tagihan</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $usages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-4 font-mono font-bold text-slate-800"><?php echo e($u->bill_number); ?></td>
                                <td class="py-3 px-4">
                                    <span class="font-bold text-slate-900 block"><?php echo e($u->customer->name ?? '-'); ?></span>
                                    <span class="font-mono text-[10px] text-slate-400"><?php echo e($u->customer->customer_number ?? ''); ?></span>
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    <?php echo e($u->customer->hamlet ?? '-'); ?> (<?php echo e($u->customer->rt_rw ?? ''); ?>)
                                </td>
                                <td class="py-3 px-4 uppercase text-[10px] font-bold">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700">
                                        <?php echo e(str_replace('_', ' ', $u->customer->category ?? 'RT')); ?>

                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center font-mono">
                                    <?php echo e($u->previous_meter); ?> &rarr; <strong><?php echo e($u->current_meter); ?></strong>
                                </td>
                                <td class="py-3 px-4 text-center font-bold text-sky-700 text-sm">
                                    <?php echo e($u->usage_m3); ?> m³
                                </td>
                                <td class="py-3 px-4 text-right">Rp <?php echo e(number_format($u->water_charge, 0, ',', '.')); ?></td>
                                <td class="py-3 px-4 text-right">Rp <?php echo e(number_format($u->subscription_fee, 0, ',', '.')); ?></td>
                                <td class="py-3 px-4 text-right font-extrabold text-slate-900">
                                    Rp <?php echo e(number_format($u->total_amount, 0, ',', '.')); ?>

                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                        <?php echo e($u->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'); ?>">
                                        <?php echo e($u->payment_status === 'lunas' ? 'LUNAS' : 'BELUM BAYAR'); ?>

                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    <?php if($u->payment_status === 'belum_lunas'): ?>
                                        <button @click="selectedUsage = <?php echo e($u->toJson()); ?>; showPayModal = true"
                                                class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[11px] font-bold shadow-sm">
                                            Bayar <i class="bi bi-cash"></i>
                                        </button>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('pamsimas.bills.print', $u->id)); ?>" target="_blank" 
                                           class="inline-flex items-center space-x-1 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[11px] font-bold" title="Cetak Struk Rekening Air">
                                            <i class="bi bi-printer text-sky-600"></i>
                                            <span>Struk</span>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="11" class="text-center py-8 text-slate-400">Belum ada tagihan meteran untuk periode ini.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                <?php echo e($usages->links()); ?>

            </div>
        </div>
    </div>

    <!-- TAB 2: DATA PELANGGAN WARGA -->
    <div x-show="currentTab === 'customers'" class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-3">
                <form action="<?php echo e(route('pamsimas.index')); ?>" method="GET" class="flex items-center space-x-2 text-xs w-full sm:w-auto">
                    <input type="hidden" name="tab" value="customers">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari Nama Warga / No PAM..." class="px-3 py-1.5 bg-slate-50 border rounded-lg">
                    <select name="hamlet" onchange="this.form.submit()" class="px-3 py-1.5 bg-slate-50 border rounded-lg">
                        <option value="">Semua Dusun</option>
                        <?php $__currentLoopData = $hamlets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($h); ?>" <?php echo e(request('hamlet') === $h ? 'selected' : ''); ?>><?php echo e($h); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="submit" class="px-3 py-1.5 bg-slate-800 text-white rounded-lg font-bold">Cari</button>
                </form>

                <button @click="showCustModal = true" class="px-3.5 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-bold">
                    + Sambungan Pelanggan Baru
                </button>
            </div>

            <div class="overflow-x-auto text-xs">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 font-semibold uppercase border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">No Sambungan</th>
                            <th class="py-3 px-4">Nama Pelanggan</th>
                            <th class="py-3 px-4">NIK</th>
                            <th class="py-3 px-4">No HP</th>
                            <th class="py-3 px-4">Dusun / Alamat</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4 text-center">Stand Terakhir</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-4 font-mono font-bold text-sky-800"><?php echo e($c->customer_number); ?></td>
                                <td class="py-3 px-4 font-bold text-slate-900"><?php echo e($c->name); ?></td>
                                <td class="py-3 px-4 font-mono text-slate-500"><?php echo e($c->nik ?: '-'); ?></td>
                                <td class="py-3 px-4 text-slate-600"><?php echo e($c->phone ?: '-'); ?></td>
                                <td class="py-3 px-4 text-slate-700"><?php echo e($c->hamlet); ?> (<?php echo e($c->rt_rw); ?>)</td>
                                <td class="py-3 px-4 uppercase text-[10px] font-bold">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700"><?php echo e(str_replace('_', ' ', $c->category)); ?></span>
                                </td>
                                <td class="py-3 px-4 text-center font-mono font-bold text-slate-800">
                                    <?php echo e($c->last_meter_reading); ?> m³
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?php echo e($c->status === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'); ?>">
                                        <?php echo e($c->status); ?>

                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <button type="button"
                                            @click="openCustomerEdit(<?php echo \Illuminate\Support\Js::from($c->only(['id', 'customer_number', 'name', 'nik', 'phone', 'hamlet', 'rt_rw', 'address', 'category', 'initial_meter', 'status', 'notes']) + ['installation_date' => $c->installation_date?->format('Y-m-d')])->toHtml() ?>)"
                                            class="text-emerald-700 hover:text-emerald-900 font-bold">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                        <form action="<?php echo e(route('pamsimas.customers.destroy', $c->id)); ?>" method="POST"
                                            onsubmit="return confirm('Hapus data pelanggan ini? Pelanggan yang memiliki riwayat tagihan tidak dapat dihapus.')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="9" class="text-center py-8 text-slate-400">Belum ada data pelanggan warga terdaftar.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                <?php echo e($customers->links()); ?>

            </div>
        </div>
    </div>

    <!-- MODAL: EDIT DATA PELANGGAN -->
    <div x-show="showCustomerEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showCustomerEditModal = false" class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-6 shadow-2xl">
            <div class="flex justify-between items-center pb-3 border-b">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Edit Data Pelanggan</h3>
                    <p class="text-[11px] text-slate-500" x-text="selectedCustomer ? selectedCustomer.customer_number || 'Data pelanggan' : ''"></p>
                </div>
                <button type="button" @click="showCustomerEditModal = false" class="text-slate-400"><i class="bi bi-x-lg"></i></button>
            </div>
            <template x-if="selectedCustomer">
            <form :action="'<?php echo e(url('/unit/pamsimas/pelanggan')); ?>/' + selectedCustomer.id + '/update'" method="POST" class="space-y-3 mt-4 text-xs">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Kepala Keluarga / Warga</label>
                    <input type="text" name="name" x-model="selectedCustomer.name" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-bold">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">NIK (KTP)</label>
                        <input type="text" name="nik" x-model="selectedCustomer.nik" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">No HP / WhatsApp</label>
                        <input type="text" name="phone" x-model="selectedCustomer.phone" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Dusun</label>
                        <input type="text" name="hamlet" x-model="selectedCustomer.hamlet" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">RT / RW</label>
                        <input type="text" name="rt_rw" x-model="selectedCustomer.rt_rw" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                    <input type="text" name="address" x-model="selectedCustomer.address" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori Pelanggan</label>
                        <select name="category" x-model="selectedCustomer.category" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-semibold">
                            <option value="rumah_tangga">Rumah Tangga</option>
                            <option value="sosial">Sosial (Masjid/Musholla)</option>
                            <option value="usaha">Usaha / Niaga</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Stand Meter Awal (m³)</label>
                        <input type="number" name="initial_meter" x-model="selectedCustomer.initial_meter" required min="0" class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-mono">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Pemasangan</label>
                        <input type="date" name="installation_date" x-model="selectedCustomer.installation_date" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Pelanggan</label>
                        <select name="status" x-model="selectedCustomer.status" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                            <option value="segel">Segel</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Catatan</label>
                    <textarea name="notes" x-model="selectedCustomer.notes" rows="2" class="w-full px-3 py-2 bg-slate-50 border rounded-lg"></textarea>
                </div>
                <div class="flex justify-end space-x-2 pt-3 border-t">
                    <button type="button" @click="showCustomerEditModal = false" class="px-4 py-2 bg-slate-100 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-lg font-bold">Simpan Perubahan</button>
                </div>
            </form>
            </template>
        </div>
    </div>

    <!-- TAB 4: PENGATURAN TARIF & ABONEMEN -->
    <div x-show="currentTab === 'tariffs'" class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 max-w-xl">
            <h3 class="font-bold text-slate-800 text-sm mb-1">Pengaturan Master Tarif Air & Abonemen BUMDes</h3>
            <p class="text-xs text-slate-500 mb-4">Tarif per m³ berlaku untuk seluruh kalkulasi rekening tagihan air</p>

            <form action="<?php echo e(route('pamsimas.settings.update')); ?>" method="POST" class="space-y-4 text-xs">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tarif Rumah Tangga (/m³)</label>
                        <input type="number" name="rate_per_m3_household" value="<?php echo e($setting->rate_per_m3_household); ?>" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-bold">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tarif Sosial (Masjid/Sekolah) (/m³)</label>
                        <input type="number" name="rate_per_m3_social" value="<?php echo e($setting->rate_per_m3_social); ?>" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tarif Usaha / Niaga (/m³)</label>
                        <input type="number" name="rate_per_m3_business" value="<?php echo e($setting->rate_per_m3_business); ?>" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-bold">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Biaya Beban / Abonemen Bulanan</label>
                        <input type="number" name="subscription_fee" value="<?php echo e($setting->subscription_fee); ?>" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-bold text-sky-700">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Denda Keterlambatan (Rp)</label>
                        <input type="number" name="late_fee" value="<?php echo e($setting->late_fee); ?>" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Jatuh Tempo Tiap Bulan</label>
                        <input type="number" name="due_date_day" value="<?php echo e($setting->due_date_day); ?>" min="1" max="28" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-bold">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Informasi Sumber Air</label>
                    <input type="text" name="water_source_info" value="<?php echo e($setting->water_source_info); ?>" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                </div>

                <button type="submit" class="w-full py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-lg font-bold transition">
                    Simpan Pengaturan Tarif
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL 1: CATAT METERAN -->
    <div x-show="showReadingModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showReadingModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="font-bold text-slate-800 text-sm">Pencatatan Stand Meter Warga</h3>
                <button @click="showReadingModal = false" class="text-slate-400"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="<?php echo e(route('pamsimas.readings.store')); ?>" method="POST" class="space-y-3 mt-4 text-xs">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Periode Tagihan</label>
                    <input type="month" name="billing_period" value="<?php echo e($period); ?>" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-bold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih Pelanggan Warga</label>
                    <select name="customer_id" x-model="selectedCustId" @change="updatePreviousMeter()" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-bold">
                        <option value="">-- Pilih Warga --</option>
                        <?php $__currentLoopData = $allCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cust): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cust->id); ?>">
                                [<?php echo e($cust->customer_number); ?>] <?php echo e($cust->name); ?> - <?php echo e($cust->hamlet); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Stand Meter Lalu (m³)</label>
                        <input type="number" name="previous_meter" x-model.number="prevMeter" required class="w-full px-3 py-2 bg-slate-100 border rounded-lg font-mono">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Stand Meter Kini (m³)</label>
                        <input type="number" name="current_meter" x-model.number="curMeter" :min="prevMeter" required class="w-full px-3 py-2 bg-white border border-sky-400 rounded-lg font-mono font-bold text-sky-800">
                    </div>
                </div>

                <div class="p-3 bg-sky-50 rounded-xl border border-sky-200">
                    <div class="flex justify-between text-sky-900 font-bold">
                        <span>Pemakaian Air:</span>
                        <span x-text="(curMeter - prevMeter) + ' m³'"></span>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tanggal Catat Meter</label>
                    <input type="date" name="reading_date" value="<?php echo e(date('Y-m-d')); ?>" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                </div>

                <div class="flex justify-end space-x-2 pt-3 border-t">
                    <button type="button" @click="showReadingModal = false" class="px-4 py-2 bg-slate-100 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-sky-600 text-white rounded-lg font-bold">Terbitkan Tagihan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: PELANGGAN BARU -->
    <div x-show="showCustModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showCustModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="font-bold text-slate-800 text-sm">Pasang Sambungan Pelanggan Baru</h3>
                <button @click="showCustModal = false" class="text-slate-400"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="<?php echo e(route('pamsimas.customers.store')); ?>" method="POST" class="space-y-3 mt-4 text-xs">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Kepala Keluarga / Warga</label>
                    <input type="text" name="name" required placeholder="Contoh: Bpk. Marwoto" class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-bold">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">NIK (KTP)</label>
                        <input type="text" name="nik" placeholder="3301..." class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">No HP / WhatsApp</label>
                        <input type="text" name="phone" placeholder="08..." class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Dusun</label>
                        <input type="text" name="hamlet" required placeholder="Dusun Krajan" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">RT / RW</label>
                        <input type="text" name="rt_rw" required placeholder="RT 02 / RW 01" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                    <input type="text" name="address" placeholder="Nama jalan / nomor rumah" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori Pelanggan</label>
                        <select name="category" class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-semibold">
                            <option value="rumah_tangga" selected>Rumah Tangga</option>
                            <option value="sosial">Sosial (Masjid/Musholla)</option>
                            <option value="usaha">Usaha / Niaga</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Stand Meter Awal (m³)</label>
                        <input type="number" name="initial_meter" value="0" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-mono">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Pemasangan</label>
                        <input type="date" name="installation_date" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Catatan</label>
                        <input type="text" name="notes" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div class="flex justify-end space-x-2 pt-3 border-t">
                    <button type="button" @click="showCustModal = false" class="px-4 py-2 bg-slate-100 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-lg font-bold">Daftarkan Warga</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 4: BAYAR TAGIHAN AIR -->
    <div x-show="showPayModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showPayModal = false" class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="font-bold text-slate-800 text-sm">Pembayaran Tagihan Air</h3>
                <button @click="showPayModal = false" class="text-slate-400"><i class="bi bi-x-lg"></i></button>
            </div>
            <form :action="'<?php echo e(url('/unit/pamsimas/bayar')); ?>/' + (selectedUsage ? selectedUsage.id : '')" method="POST" class="space-y-3 mt-4 text-xs">
                <?php echo csrf_field(); ?>
                <div class="p-3 bg-sky-50 rounded-xl text-sky-950 space-y-1">
                    <p class="font-bold text-sm" x-text="selectedUsage ? selectedUsage.customer.name : ''"></p>
                    <p class="text-[11px] text-slate-600" x-text="'No Tagihan: ' + (selectedUsage ? selectedUsage.bill_number : '')"></p>
                    <div class="pt-2 border-t border-sky-200 flex justify-between font-extrabold text-sm">
                        <span>Total Tagihan:</span>
                        <span class="text-emerald-700" x-text="'Rp ' + (selectedUsage ? Number(selectedUsage.total_amount).toLocaleString('id-ID') : 0)"></span>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Metode Pembayaran</label>
                    <select name="payment_method" class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-bold">
                        <option value="tunai" selected>Kas Tunai (Loket Pamsimas)</option>
                        <option value="transfer_bank">Transfer Bank BRI BUMDes</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-2 pt-3 border-t">
                    <button type="button" @click="showPayModal = false" class="px-4 py-2 bg-slate-100 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-lg font-bold">Konfirmasi Lunas & Masuk Kas</button>
                </div>
            </form>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\BumdesAntigravity\resources\views/unit_pamsimas/index.blade.php ENDPATH**/ ?>