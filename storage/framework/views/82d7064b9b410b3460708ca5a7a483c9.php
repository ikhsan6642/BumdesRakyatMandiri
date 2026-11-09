<?php $__env->startSection('title', 'Unit 3: CFD & Pasar UMKM Minggu'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="{ 
    currentTab: '<?php echo e($tab); ?>', 
    showEventModal: false, 
    showTicketModal: false, 
    showVendorModal: false, 
    showVendorEditModal: false,
    showZoneEditModal: false,
    selectedZone: null,
    availableSpotsByZone: <?php echo \Illuminate\Support\Js::from($availableSpotsByZone)->toHtml() ?>,
    registrationZoneId: '',
    registrationSpotNumber: '',
    selectedVendor: null,
    editVendorSpotNumber: '',
    availableSpotsByVendor: <?php echo \Illuminate\Support\Js::from($availableSpotsByVendor)->toHtml() ?>,
    openVendorEdit(vendor) {
        this.selectedVendor = vendor;
        this.editVendorSpotNumber = vendor.spot_number || '';
        this.showVendorEditModal = true;
    },
    openZoneCreate() {
        this.selectedZone = { id: null, name: '', code: '', default_fee: '', capacity_spots: 50, description: '' };
        this.showZoneEditModal = true;
    },
    openZoneEdit(zone) {
        this.selectedZone = zone;
        this.showZoneEditModal = true;
    },
    ticketVendorId: '',
    ticketZoneId: '',
    ticketSpotNumber: '',
    ticketFee: '',
    ticketEventId: <?php echo \Illuminate\Support\Js::from($events->first()?->id)->toHtml() ?>,
    ticketedVendorIdsByEvent: <?php echo \Illuminate\Support\Js::from($ticketedVendorIdsByEvent)->toHtml() ?>,
    ticketVendorLabel(vendorId, vendorName, ownerName) {
        const ticketedVendorIds = this.ticketedVendorIdsByEvent[this.ticketEventId] || [];
        const hasTicket = ticketedVendorIds.includes(Number(vendorId));
        return `${vendorName} (${ownerName}) — ${hasTicket ? '✓ SUDAH DISIMPAN' : 'BELUM DIINPUT'}`;
    },
    selectTicketZone(event) {
        this.ticketZoneId = event.target.value;
        this.ticketFee = event.target.selectedOptions[0].dataset.defaultFee || '';
    },
    selectTicketVendor(event) {
        const option = event.target.selectedOptions[0];
        this.ticketVendorId = event.target.value;
        this.ticketZoneId = option.dataset.zoneId || '';
        this.ticketSpotNumber = option.dataset.spotNumber || '';
        this.ticketFee = option.dataset.defaultFee || '';
    },
    eventDate: '<?php echo e(now()->next(\Carbon\Carbon::SUNDAY)->toDateString()); ?>',
    get eventWeekday() {
        if (!this.eventDate) return '';
        const [year, month, day] = this.eventDate.split('-').map(Number);
        return new Intl.DateTimeFormat('id-ID', { weekday: 'long' }).format(new Date(year, month - 1, day));
    }
}">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <span class="w-7 h-7 rounded-lg bg-rose-100 text-rose-800 flex items-center justify-center font-bold text-sm">
                    <i class="bi bi-shop"></i>
                </span>
                <span class="text-xs uppercase font-extrabold text-rose-700 tracking-wider">Unit Usaha 3</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                Car Free Day (CFD) & Pasar UMKM Minggu
            </h1>
            <p class="text-xs text-slate-500">
                Retribusi lapak pedagang UMKM warga dan pengelolaan pasar CFD
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button @click="showTicketModal = true" class="px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-sm">
                <i class="bi bi-ticket-perforated-fill"></i>
                <span>Pungut Karcis Lapak</span>
            </button>
            <button @click="showEventModal = true" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-sm">
                <i class="bi bi-calendar-plus"></i>
                <span>Jadwalkan Event CFD</span>
            </button>
            <button @click="showVendorModal = true" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-sm">
                <i class="bi bi-person-plus-fill"></i>
                <span>Daftar UMKM Baru</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm max-w-md">
            <span class="text-xs text-slate-500 font-semibold uppercase">Retribusi Karcis Lapak</span>
            <h3 class="text-xl font-extrabold text-rose-600 mt-1">Rp <?php echo e(number_format($totalStallRevenue, 0, ',', '.')); ?></h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Sewa lapak pedagang kuliner & fashion</p>
        </div>
    </div>

    <!-- TABS -->
    <div class="flex border-b border-slate-200 space-x-6 text-xs font-bold">
        <button @click="currentTab = 'events'"
            :class="currentTab === 'events' ? 'border-b-2 border-rose-600 text-rose-700 pb-2.5' : 'text-slate-500 hover:text-slate-700 pb-2.5'">
            <i class="bi bi-calendar-event mr-1"></i> Rekap Event CFD Mingguan
        </button>
        <button @click="currentTab = 'tickets'"
            :class="currentTab === 'tickets' ? 'border-b-2 border-rose-600 text-rose-700 pb-2.5' : 'text-slate-500 hover:text-slate-700 pb-2.5'">
            <i class="bi bi-ticket-perforated mr-1"></i> Karcis Retribusi Lapak
        </button>
        <button @click="currentTab = 'vendors'"
            :class="currentTab === 'vendors' ? 'border-b-2 border-rose-600 text-rose-700 pb-2.5' : 'text-slate-500 hover:text-slate-700 pb-2.5'">
            <i class="bi bi-shop-window mr-1"></i> Direktori Pedagang UMKM (<?php echo e($activeVendorsCount); ?>)
        </button>
        <button @click="currentTab = 'zones'"
            :class="currentTab === 'zones' ? 'border-b-2 border-rose-600 text-rose-700 pb-2.5' : 'text-slate-500 hover:text-slate-700 pb-2.5'">
            <i class="bi bi-geo mr-1"></i> Zona & Tarif Lapak
        </button>
    </div>

    <!-- TAB 1: EVENTS CFD -->
    <div x-show="currentTab === 'events'" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-mono text-xs font-bold text-slate-800"><?php echo e($event->event_code); ?></span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                <?php echo e($event->status === 'selesai' ? 'bg-slate-100 text-slate-700' : ($event->status === 'terjadwal' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800')); ?>">
                            <?php echo e($event->status); ?>

                        </span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base"><?php echo e($event->name); ?></h3>
                    <p class="text-xs text-slate-500 mt-0.5"><i class="bi bi-calendar"></i> <?php echo e($event->event_date->locale('id')->translatedFormat('l, d M Y')); ?></p>
                    <p class="text-xs text-slate-500 mt-0.5"><i class="bi bi-clock"></i> Pemungutan karcis <?php echo e(substr($event->collection_start_time, 0, 5)); ?>–<?php echo e(substr($event->collection_end_time, 0, 5)); ?></p>

                    <!-- Ringkasan Karcis Lapak -->
                    <div class="my-3 py-2 border-y border-slate-100 text-xs space-y-1">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Lapak Buka:</span>
                            <span class="font-bold text-slate-800"><?php echo e($event->total_stalls_occupied); ?> Lapak</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Karcis Lapak:</span>
                            <span class="font-semibold text-rose-600">Rp <?php echo e(number_format($event->total_stall_revenue, 0, ',', '.')); ?></span>
                        </div>
                    </div>
                </div>

                <div class="mt-2 pt-2 border-t border-slate-100 flex flex-col items-start gap-3 text-xs">
                    <span class="text-[11px] text-slate-400 truncate max-w-[150px]"><?php echo e($event->notes ?: 'CFD berjalan sukses'); ?></span>
                    <form action="<?php echo e(route('cfd.events.destroy', $event->id)); ?>" method="POST"
                        onsubmit="return confirm('Hapus event ini beserta seluruh karcis dan transaksi terkait? Tindakan ini tidak dapat dibatalkan.')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold">
                            <i class="bi bi-trash"></i> Hapus Event
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-3 text-center py-10 bg-white rounded-2xl border text-slate-400 text-xs">
                Belum ada event CFD yang dijadwalkan.
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- TAB 2: KARCIS RETRIBUSI LAPAK -->
    <div x-show="currentTab === 'tickets'" class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-sm">Daftar Karcis Retribusi Lapak Pedagang CFD</h3>
                <button @click="showTicketModal = true" class="px-3 py-1.5 bg-rose-600 text-white rounded-lg text-xs font-bold">
                    + Pungut Karcis Baru
                </button>
            </div>
            <div class="overflow-x-auto text-xs">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 font-semibold uppercase border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">No Karcis</th>
                            <th class="py-3 px-4">Event CFD</th>
                            <th class="py-3 px-4">Pedagang / Usaha</th>
                            <th class="py-3 px-4">Zona / No Lapak</th>
                            <th class="py-3 px-4">Retribusi Pokok</th>
                            <th class="py-3 px-4">Biaya Listrik/Sampah</th>
                            <th class="py-3 px-4 text-right">Total Karcis</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 font-mono font-bold text-slate-800"><?php echo e($t->ticket_number); ?></td>
                            <td class="py-3 px-4 text-slate-600"><?php echo e($t->event->name ?? '-'); ?></td>
                            <td class="py-3 px-4 font-semibold text-slate-800">
                                <?php echo e($t->vendor ? $t->vendor->name : ($t->vendor_name_manual ?: 'Pedagang Insidental')); ?>

                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-rose-700"><?php echo e($t->spot_number); ?></span>
                                <span class="text-slate-400 text-[11px]">(<?php echo e($t->zone->code ?? ''); ?>)</span>
                            </td>
                            <td class="py-3 px-4">Rp <?php echo e(number_format($t->fee_amount, 0, ',', '.')); ?></td>
                            <td class="py-3 px-4">Rp <?php echo e(number_format($t->electricity_fee + $t->cleanliness_fee, 0, ',', '.')); ?></td>
                            <td class="py-3 px-4 text-right font-extrabold text-rose-600">Rp <?php echo e(number_format($t->total_amount, 0, ',', '.')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-6 text-slate-400">Belum ada karcis retribusi tercatat.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                <?php echo e($tickets->links()); ?>

            </div>
        </div>
    </div>

    <!-- TAB 3: DIREKTORI PEDAGANG UMKM -->
    <div x-show="currentTab === 'vendors'" class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-sm">Direktori Pedagang UMKM Binaan CFD BUMDes</h3>
                <button @click="showVendorModal = true" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-bold">
                    + Daftarkan Pedagang Baru
                </button>
            </div>
            <div class="overflow-x-auto text-xs">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 font-semibold uppercase border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Kode Pedagang</th>
                            <th class="py-3 px-4">Nama Toko / Lapak</th>
                            <th class="py-3 px-4">Pemilik & Kontak</th>
                            <th class="py-3 px-4">Kategori Jualan</th>
                            <th class="py-3 px-4">Zona Favorit</th>
                            <th class="py-3 px-4">Asal Desa</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 font-mono font-bold text-slate-800"><?php echo e($v->vendor_code); ?></td>
                            <td class="py-3 px-4 font-bold text-slate-900"><?php echo e($v->name); ?></td>
                            <td class="py-3 px-4">
                                <span class="block text-slate-800"><?php echo e($v->owner_name); ?></span>
                                <span class="text-slate-400 text-[11px]"><?php echo e($v->phone ?: '-'); ?></span>
                            </td>
                            <td class="py-3 px-4 uppercase text-[10px] font-bold">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700"><?php echo e(str_replace('_', ' ', $v->category)); ?></span>
                            </td>
                            <td class="py-3 px-4 font-medium"><?php echo e($v->preferredZone->name ?? 'Fleksibel'); ?> (<?php echo e($v->spot_number ?: '-'); ?>)</td>
                            <td class="py-3 px-4 text-slate-600"><?php echo e($v->origin_village); ?></td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?php echo e($v->status === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600'); ?>">
                                    <?php echo e($v->status); ?>

                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <button type="button"
                                        @click="openVendorEdit(<?php echo \Illuminate\Support\Js::from($v->only(['id', 'name', 'owner_name', 'phone', 'preferred_zone_id', 'spot_number', 'category', 'origin_village', 'status', 'notes']) + ['available_spots_by_zone' => $availableSpotsByVendor[$v->id]])->toHtml() ?>)"
                                        class="text-emerald-700 hover:text-emerald-900 font-bold">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <form action="<?php echo e(route('cfd.vendors.destroy', $v->id)); ?>" method="POST"
                                        onsubmit="return confirm('Hapus pedagang ini dari direktori? Riwayat karcis tetap tersimpan.')">
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
                        <tr>
                            <td colspan="8" class="text-center py-6 text-slate-400">Belum ada pedagang terdaftar.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                <?php echo e($vendors->links()); ?>

            </div>
        </div>
    </div>

    <!-- TAB 4: ZONA & TARIF RETRIBUSI -->
    <div x-show="currentTab === 'zones'" class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Zona Lapak CFD & Tarif Dasar Retribusi</h3>
            <button @click="openZoneCreate()" class="px-3 py-1.5 bg-slate-800 text-white rounded-lg text-xs font-bold">
                + Tambah Zona Baru
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $z): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-mono text-xs font-bold text-slate-500"><?php echo e($z->code); ?></span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                            Kapasitas: <?php echo e($z->capacity_spots); ?> Lapak
                        </span>
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm"><?php echo e($z->name); ?></h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed"><?php echo e($z->description ?: 'Zona khusus pedagang'); ?></p>

                    <div class="my-3 pt-2 border-t border-slate-100 text-xs">
                        <span class="text-slate-500">Tarif Retribusi Pokok:</span>
                        <span class="font-bold text-rose-700 ml-1">Rp <?php echo e(number_format($z->default_fee, 0, ',', '.')); ?> / lapak</span>
                    </div>
                </div>
                <div class="text-[11px] text-slate-400 pt-2 border-t border-slate-100">
                    <div class="flex items-center justify-between gap-2">
                        <span>Pedagang Terdaftar di Zona Ini: <strong class="text-slate-700"><?php echo e($z->vendors_count); ?> UMKM</strong></span>
                        <div class="flex items-center gap-3 shrink-0">
                            <button type="button"
                                @click="openZoneEdit(<?php echo \Illuminate\Support\Js::from($z->only(['id', 'name', 'code', 'default_fee', 'capacity_spots', 'description']))->toHtml() ?>)"
                                class="font-bold text-emerald-700 hover:text-emerald-900">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <?php if($z->tickets_count || $z->vendors_count): ?>
                            <button type="button" disabled title="Pindahkan pedagang dan karcis terlebih dahulu"
                                class="font-bold text-slate-300 cursor-not-allowed">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                            <?php else: ?>
                            <form action="<?php echo e(route('cfd.zones.destroy', $z->id)); ?>" method="POST"
                                onsubmit="return confirm('Hapus zona <?php echo e(addslashes($z->name)); ?>? Tindakan ini tidak dapat dibatalkan.')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="font-bold text-rose-600 hover:text-rose-800">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if($z->tickets_count || $z->vendors_count): ?>
                    <p class="mt-2 text-[10px] text-slate-400">Zona hanya bisa dihapus setelah pedagang dan karcis terkait dipindahkan atau diselesaikan.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div x-show="showZoneEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showZoneEditModal = false" class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto p-6 shadow-2xl">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="font-bold text-slate-800 text-sm" x-text="selectedZone && selectedZone.id ? 'Edit Zona & Tarif Lapak' : 'Tambah Zona Lapak'"></h3>
                <button type="button" @click="showZoneEditModal = false" class="text-slate-400"><i class="bi bi-x-lg"></i></button>
            </div>
            <form :action="selectedZone && selectedZone.id ? '<?php echo e(url('/unit/cfd/zona')); ?>/' + selectedZone.id + '/update' : '<?php echo e(route('cfd.zones.store')); ?>'" method="POST" class="space-y-3 mt-4 text-xs">
                <?php echo csrf_field(); ?>
                <template x-if="selectedZone && selectedZone.id">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Zona</label>
                    <input type="text" name="name" x-model="selectedZone.name" required maxlength="255" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Zona</label>
                        <input type="text" name="code" x-model="selectedZone.code" required maxlength="50" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tarif Dasar (Rp)</label>
                        <input type="number" name="default_fee" x-model="selectedZone.default_fee" required min="0" step="0.01" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kapasitas Lapak</label>
                    <input type="number" name="capacity_spots" x-model="selectedZone.capacity_spots" required min="1" step="1" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi</label>
                    <textarea name="description" x-model="selectedZone.description" rows="2" class="w-full px-3 py-2 bg-slate-50 border rounded-lg"></textarea>
                </div>
                <div class="flex justify-end space-x-2 pt-3 border-t">
                    <button type="button" @click="showZoneEditModal = false" class="px-4 py-2 bg-slate-100 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-lg font-bold" x-text="selectedZone && selectedZone.id ? 'Simpan Perubahan' : 'Simpan Zona'"></button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 1: EVENT CFD BARU -->
    <div x-show="showEventModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showEventModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="font-bold text-slate-800 text-sm">Jadwalkan Event CFD Baru</h3>
                <button @click="showEventModal = false" class="text-slate-400"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="<?php echo e(route('cfd.events.store')); ?>" method="POST" class="space-y-3 mt-4 text-xs">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Event</label>
                    <input type="text" name="name" required placeholder="Contoh: CFD Rakyat Mandiri Pekan ke-43" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tanggal Event</label>
                    <input type="date" name="event_date" x-model="eventDate" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    <p class="mt-1 text-slate-500">Hari: <span class="font-bold capitalize" x-text="eventWeekday"></span>. Hari Minggu menjadi prioritas jadwal CFD.</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Mulai Pemungutan</label>
                        <input type="time" name="collection_start_time" value="07:00" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Selesai Pemungutan</label>
                        <input type="time" name="collection_end_time" value="11:00" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Catatan Tambahan</label>
                    <textarea name="notes" rows="2" placeholder="Senam pagi PKK, panggung kreasi warga, dll..." class="w-full px-3 py-2 bg-slate-50 border rounded-lg"></textarea>
                </div>
                <div class="flex justify-end space-x-2 pt-3 border-t">
                    <button type="button" @click="showEventModal = false" class="px-4 py-2 bg-slate-100 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-slate-800 text-white rounded-lg font-bold">Buat Event CFD</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: PUNGUT KARCIS LAPAK -->
    <div x-show="showTicketModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showTicketModal = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="font-bold text-slate-800 text-sm">Pungut Karcis Retribusi Lapak</h3>
                <button @click="showTicketModal = false" class="text-slate-400"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="<?php echo e(route('cfd.tickets.store')); ?>" method="POST" class="space-y-3 mt-4 text-xs">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih Event CFD</label>
                    <select name="event_id" x-model="ticketEventId" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($ev->id); ?>">[<?php echo e($ev->event_code); ?>] <?php echo e($ev->name); ?> (<?php echo e($ev->event_date->locale('id')->translatedFormat('l, d/m/Y')); ?> <?php echo e(substr($ev->collection_start_time, 0, 5)); ?>–<?php echo e(substr($ev->collection_end_time, 0, 5)); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <p class="mt-1 text-[10px] text-slate-500">Status pedagang mengikuti event yang dipilih.</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pedagang Terdaftar</label>
                        <select name="vendor_id" x-model="ticketVendorId" @change="selectTicketVendor($event)" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                            <option value="">-- Pedagang Insidental / Baru --</option>
                            <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vnd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($vnd->id); ?>"
                                data-zone-id="<?php echo e($vnd->preferred_zone_id); ?>"
                                data-spot-number="<?php echo e($vnd->spot_number); ?>"
                                data-default-fee="<?php echo e($vnd->preferredZone?->default_fee); ?>"
                                x-text="ticketVendorLabel(<?php echo \Illuminate\Support\Js::from($vnd->id)->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($vnd->name)->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($vnd->owner_name)->toHtml() ?>)">
                                <?php echo e($vnd->name); ?> (<?php echo e($vnd->owner_name); ?>) — BELUM DIINPUT
                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Pedagang (Jika Baru)</label>
                        <input type="text" name="vendor_name_manual" placeholder="Warung Es Jeruk Segar" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pilih Zona</label>
                        <select name="zone_id" x-model="ticketZoneId" @change="selectTicketZone($event)" :disabled="ticketVendorId !== ''" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                            <option value="">-- Pilih Zona --</option>
                            <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($zn->id); ?>" data-default-fee="<?php echo e($zn->default_fee); ?>"><?php echo e($zn->name); ?> - Rp <?php echo e(number_format($zn->default_fee, 0, ',', '.')); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <input type="hidden" name="zone_id" :value="ticketZoneId" :disabled="ticketVendorId === ''">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nomor Spot Lapak</label>
                        <input type="text" name="spot_number" x-model="ticketSpotNumber" :readonly="ticketVendorId !== ''" required placeholder="A-15" class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-bold">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tarif Pokok (Rp)</label>
                        <input type="number" name="fee_amount" x-model="ticketFee" readonly required placeholder="Otomatis dari tarif zona" class="w-full px-3 py-2 bg-slate-100 border rounded-lg font-bold">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Biaya Listrik (Rp)</label>
                        <input type="number" name="electricity_fee" value="0" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Biaya Sampah (Rp)</label>
                        <input type="number" name="cleanliness_fee" value="2000" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <input type="hidden" name="payment_status" value="lunas">
                <div class="flex justify-end space-x-2 pt-3 border-t">
                    <button type="button" @click="showTicketModal = false" class="px-4 py-2 bg-slate-100 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 text-white rounded-lg font-bold">Simpan Karcis</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: DAFTAR UMKM -->
    <div x-show="showVendorModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showVendorModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="font-bold text-slate-800 text-sm">Pendaftaran Pedagang UMKM Baru</h3>
                <button @click="showVendorModal = false" class="text-slate-400"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="<?php echo e(route('cfd.vendors.store')); ?>" method="POST" class="space-y-3 mt-4 text-xs">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Usaha / Lapak</label>
                    <input type="text" name="name" required placeholder="Contoh: Pempek Palembang Bu Dewi" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Pemilik</label>
                        <input type="text" name="owner_name" required placeholder="Ibu Dewi" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">No HP</label>
                        <input type="text" name="phone" placeholder="08..." class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori Usaha</label>
                        <select name="category" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                            <option value="kuliner_makanan">Kuliner Makanan</option>
                            <option value="kuliner_minuman">Kuliner Minuman</option>
                            <option value="fashion_pakaian">Fashion & Pakaian</option>
                            <option value="kerajinan_souvenir">Kerajinan Desa</option>
                            <option value="mainan_hiburan">Mainan & Jasa Hiburan</option>
                            <option value="pertanian_organik">Hasil Tani / Buah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Asal Warga</label>
                        <input type="text" name="origin_village" value="Desa Rakyat Mandiri" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Zona Lapak Preferensi</label>
                    <select name="preferred_zone_id" x-model="registrationZoneId" @change="registrationSpotNumber = ''" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                        <option value="">-- Pilih Zona --</option>
                        <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($zn->id); ?>"><?php echo e($zn->name); ?> (<?php echo e(count($availableSpotsByZone[$zn->id] ?? [])); ?> lapak kosong)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nomor Lapak Kosong</label>
                    <select name="spot_number" x-model="registrationSpotNumber" :disabled="!registrationZoneId" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-bold">
                        <option value="">-- Pilih Nomor Lapak --</option>
                        <template x-for="spotNumber in (availableSpotsByZone[registrationZoneId] || [])" :key="spotNumber">
                            <option :value="spotNumber" x-text="spotNumber"></option>
                        </template>
                    </select>
                    <p x-show="registrationZoneId && !(availableSpotsByZone[registrationZoneId] || []).length" class="mt-1 text-rose-600">
                        Semua lapak di zona ini sudah terisi.
                    </p>
                </div>
                <input type="hidden" name="status" value="aktif">
                <div class="flex justify-end space-x-2 pt-3 border-t">
                    <button type="button" @click="showVendorModal = false" class="px-4 py-2 bg-slate-100 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-lg font-bold">Simpan Pedagang</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showVendorEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showVendorEditModal = false" class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto p-6 shadow-2xl">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="font-bold text-slate-800 text-sm">Edit Data Pedagang UMKM</h3>
                <button type="button" @click="showVendorEditModal = false" class="text-slate-400"><i class="bi bi-x-lg"></i></button>
            </div>
            <form :action="'<?php echo e(url('/unit/cfd/pedagang')); ?>/' + (selectedVendor ? selectedVendor.id : '') + '/update'" method="POST" class="space-y-3 mt-4 text-xs">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Usaha / Lapak</label>
                    <input type="text" name="name" x-model="selectedVendor.name" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Pemilik</label>
                        <input type="text" name="owner_name" x-model="selectedVendor.owner_name" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">No HP</label>
                        <input type="text" name="phone" x-model="selectedVendor.phone" class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori Usaha</label>
                        <select name="category" x-model="selectedVendor.category" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                            <option value="kuliner_makanan">Kuliner Makanan</option>
                            <option value="kuliner_minuman">Kuliner Minuman</option>
                            <option value="fashion_pakaian">Fashion & Pakaian</option>
                            <option value="kerajinan_souvenir">Kerajinan Desa</option>
                            <option value="mainan_hiburan">Mainan & Jasa Hiburan</option>
                            <option value="pertanian_organik">Hasil Tani / Buah</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Asal Warga</label>
                        <input type="text" name="origin_village" x-model="selectedVendor.origin_village" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Zona Lapak Preferensi</label>
                    <select name="preferred_zone_id" x-model="selectedVendor.preferred_zone_id" @change="editVendorSpotNumber = ''" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                        <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($zn->id); ?>"><?php echo e($zn->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nomor Lapak Kosong</label>
                    <select name="spot_number" x-model="editVendorSpotNumber" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg font-bold">
                        <option value="">-- Pilih Nomor Lapak --</option>
                        <template x-for="spotNumber in (selectedVendor ? (selectedVendor.available_spots_by_zone[selectedVendor.preferred_zone_id] || []) : [])" :key="spotNumber">
                            <option :value="spotNumber" x-text="spotNumber"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status</label>
                    <select name="status" x-model="selectedVendor.status" required class="w-full px-3 py-2 bg-slate-50 border rounded-lg">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Catatan</label>
                    <textarea name="notes" x-model="selectedVendor.notes" rows="2" class="w-full px-3 py-2 bg-slate-50 border rounded-lg"></textarea>
                </div>
                <div class="flex justify-end space-x-2 pt-3 border-t">
                    <button type="button" @click="showVendorEditModal = false" class="px-4 py-2 bg-slate-100 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-lg font-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\BumdesRakyatMandiri\resources\views/unit_cfd/index.blade.php ENDPATH**/ ?>