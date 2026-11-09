<?php $__env->startSection('title', 'Bagi Hasil Laba Bersih & PADes'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                Alokasi Bagi Hasil Usaha BUMDes & PADes
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Pembagian Laba Bersih Tahunan untuk Pendapatan Asli Desa (PADes), Cadangan Modal, dan Dana Sosial sesuai Permendesa
            </p>
        </div>
    </div>

    <!-- CURRENT YEAR NET PROFIT BANNER -->
    <div class="bg-gradient-to-r from-emerald-700 to-teal-800 p-6 rounded-2xl text-white shadow-lg flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs uppercase font-bold text-emerald-200 tracking-wider">Laba Bersih Konsolidasi Tahun <?php echo e($year); ?></span>
            <h2 class="text-3xl font-black mt-1">Rp <?php echo e(number_format($currentYearNetProfit, 0, ',', '.')); ?></h2>
            <p class="text-xs text-emerald-100/80 mt-1">
                Akumulasi surplus seluruh 4 unit usaha yang siap dibagihasilkan pada Musyawarah Desa (Musdes).
            </p>
        </div>
        <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20 text-xs">
            <span class="font-bold text-emerald-300 block mb-1">Standar Alokasi AD/ART:</span>
            <div class="space-y-0.5 text-emerald-100">
                <p>&bull; PADes (Kas Desa): <strong>35%</strong></p>
                <p>&bull; Cadangan Modal: <strong>25%</strong></p>
                <p>&bull; Jasa Pengurus: <strong>20%</strong></p>
                <p>&bull; Dana Sosial & Edukasi: <strong>20%</strong></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- FORM SIMULASI & PENETAPAN ALOKASI (5 cols) -->
        <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm"
             x-data="{
                profit: <?php echo e($selectedDistribution ? $selectedDistribution->total_net_profit : $currentYearNetProfit); ?>,
                padesPct: <?php echo e($selectedDistribution ? $selectedDistribution->pades_percentage : 35); ?>,
                capitalPct: <?php echo e($selectedDistribution ? $selectedDistribution->capital_reserve_percentage : 25); ?>,
                managePct: <?php echo e($selectedDistribution ? $selectedDistribution->management_incentive_percentage : 20); ?>,
                socialPct: <?php echo e($selectedDistribution ? $selectedDistribution->social_fund_percentage : 10); ?>,
                eduPct: <?php echo e($selectedDistribution ? $selectedDistribution->education_fund_percentage : 10); ?>,
                calc(pct) { return Math.round((pct / 100) * this.profit); }
             }">
            <h3 class="font-bold text-slate-800 text-sm mb-1">Form Pengesahan Pembagian Laba</h3>
            <p class="text-xs text-slate-500 mb-4">Input persentase hasil Musdes untuk tahun buku <?php echo e($year); ?></p>

            <form action="<?php echo e(route('finance.profit_distribution.store')); ?>" method="POST" class="space-y-4 text-xs">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="fiscal_year" value="<?php echo e($year); ?>">
                <input type="hidden" name="total_net_profit" :value="profit">

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Total Laba Bersih yang Dibagi (Rp)</label>
                    <input type="number" x-model.number="profit" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg font-bold text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>

                <!-- PADes -->
                <div class="p-3 bg-emerald-50/60 rounded-xl border border-emerald-200">
                    <div class="flex justify-between items-center mb-1">
                        <label class="font-bold text-emerald-900">1. Pendapatan Asli Desa (PADes)</label>
                        <span class="text-emerald-700 font-extrabold" x-text="'Rp ' + calc(padesPct).toLocaleString('id-ID')"></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="number" name="pades_percentage" x-model.number="padesPct" min="0" max="100" step="0.5" required
                               class="w-20 px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-center font-bold">
                        <span class="text-slate-500 font-semibold">% dari laba</span>
                    </div>
                </div>

                <!-- Cadangan Modal -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="flex justify-between items-center mb-1">
                        <label class="font-bold text-slate-800">2. Cadangan Modal BUMDes</label>
                        <span class="text-slate-800 font-extrabold" x-text="'Rp ' + calc(capitalPct).toLocaleString('id-ID')"></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="number" name="capital_reserve_percentage" x-model.number="capitalPct" min="0" max="100" step="0.5" required
                               class="w-20 px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-center font-bold">
                        <span class="text-slate-500 font-semibold">% dari laba</span>
                    </div>
                </div>

                <!-- Jasa Pengurus -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="flex justify-between items-center mb-1">
                        <label class="font-bold text-slate-800">3. Jasa Pengurus, Pengawas & Pegawai</label>
                        <span class="text-slate-800 font-extrabold" x-text="'Rp ' + calc(managePct).toLocaleString('id-ID')"></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="number" name="management_incentive_percentage" x-model.number="managePct" min="0" max="100" step="0.5" required
                               class="w-20 px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-center font-bold">
                        <span class="text-slate-500 font-semibold">% dari laba</span>
                    </div>
                </div>

                <!-- Dana Sosial -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="flex justify-between items-center mb-1">
                        <label class="font-bold text-slate-800">4. Dana Sosial Warga Miskin</label>
                        <span class="text-slate-800 font-extrabold" x-text="'Rp ' + calc(socialPct).toLocaleString('id-ID')"></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="number" name="social_fund_percentage" x-model.number="socialPct" min="0" max="100" step="0.5" required
                               class="w-20 px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-center font-bold">
                        <span class="text-slate-500 font-semibold">% dari laba</span>
                    </div>
                </div>

                <!-- Dana Pendidikan -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="flex justify-between items-center mb-1">
                        <label class="font-bold text-slate-800">5. Dana Pendidikan & Pembinaan</label>
                        <span class="text-slate-800 font-extrabold" x-text="'Rp ' + calc(eduPct).toLocaleString('id-ID')"></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="number" name="education_fund_percentage" x-model.number="eduPct" min="0" max="100" step="0.5" required
                               class="w-20 px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-center font-bold">
                        <span class="text-slate-500 font-semibold">% dari laba</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Pengesahan</label>
                        <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg font-semibold">
                            <option value="draft">Draft Musdes</option>
                            <option value="disetujui_musdes" selected>Disetujui Musdes</option>
                            <option value="disalurkan">Telah Disalurkan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Disahkan Oleh</label>
                        <input type="text" name="approved_by" value="Drs. H. Mulyono (Kepala Desa)"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-md transition">
                    Simpan & Tetapkan Alokasi PADes
                </button>
            </form>
        </div>

        <!-- RIWAYAT ALOKASI TAHUN-TAHUN SEBELUMNYA (7 cols) -->
        <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-800 text-sm mb-1">Riwayat Kontribusi PADes BUMDes</h3>
            <p class="text-xs text-slate-500 mb-4">Catatan penyaluran Pendapatan Asli Desa per tahun anggaran</p>

            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $distributions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:shadow-sm transition">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-800 text-white font-extrabold text-xs">
                                    Tahun <?php echo e($dist->fiscal_year); ?>

                                </span>
                                <span class="text-xs font-bold text-slate-800">
                                    Laba Bersih: Rp <?php echo e(number_format($dist->total_net_profit, 0, ',', '.')); ?>

                                </span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?php echo e($dist->status === 'disalurkan' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'); ?>">
                                <?php echo e($dist->status); ?>

                            </span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs py-2 border-y border-slate-200/60 my-2">
                            <div>
                                <span class="text-slate-400 block text-[11px]">Setor Kas Desa (PADes):</span>
                                <span class="font-extrabold text-emerald-700">Rp <?php echo e(number_format($dist->pades_amount, 0, ',', '.')); ?> (<?php echo e($dist->pades_percentage); ?>%)</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[11px]">Cadangan Modal:</span>
                                <span class="font-bold text-slate-700">Rp <?php echo e(number_format($dist->capital_reserve_amount, 0, ',', '.')); ?></span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[11px]">Jasa Pengurus & Karyawan:</span>
                                <span class="font-bold text-slate-700">Rp <?php echo e(number_format($dist->management_incentive_amount, 0, ',', '.')); ?></span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[11px]">Dana Sosial Warga:</span>
                                <span class="font-bold text-slate-700">Rp <?php echo e(number_format($dist->social_fund_amount, 0, ',', '.')); ?></span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[11px]">Dana Pendidikan:</span>
                                <span class="font-bold text-slate-700">Rp <?php echo e(number_format($dist->education_fund_amount, 0, ',', '.')); ?></span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[11px]">Pengesahan:</span>
                                <span class="font-medium text-slate-600 truncate block"><?php echo e($dist->approved_by ?: 'Musdes'); ?></span>
                            </div>
                        </div>

                        <p class="text-[11px] text-slate-500 italic">
                            Catatan: <?php echo e($dist->notes ?: 'Telah disahkan dalam Musyawarah Desa (Musdes).'); ?>

                        </p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-8 text-slate-400 text-xs">
                        Belum ada riwayat pembagian laba yang dicatat.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\BumdesAntigravity\resources\views/finance/profit_distribution.blade.php ENDPATH**/ ?>