<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Sistem Informasi Keuangan'); ?> - BUMDes Rakyat Mandiri</title>
    <link rel="icon" type="image/jpeg" href="<?php echo e(asset('logo-bumdes.jpeg')); ?>">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        },
                        bumdes: {
                            gold: '#f59e0b',
                            blue: '#0284c7',
                            emerald: '#059669',
                            rose: '#e11d48',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            body {
                background: white !important;
                color: black !important;
            }

            .print-card {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
            }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col" x-data="{ sidebarOpen: false }">

    <div class="flex flex-1 min-h-screen">
        <!-- SIDEBAR -->
        <aside class="no-print fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-100 flex flex-col transition-transform duration-300 transform lg:translate-x-0 lg:sticky lg:top-0 lg:bottom-auto lg:left-auto lg:h-screen lg:shrink-0 shadow-xl"
            :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">

            <!-- Logo Header -->
            <div class="p-5 border-b border-slate-800 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-14 h-14 shrink-0 rounded-full bg-white p-1 ring-2 ring-emerald-400/70 shadow-lg shadow-emerald-900/30">
                        <img id="bumdes-logo" src="<?php echo e(asset('logo-bumdes.jpeg')); ?>" alt="Logo BUMDes Rakyat Mandiri" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div>
                        <h1 class="text-lg font-extrabold tracking-wide text-white leading-tight">BUMDes</h1>
                        <p class="text-xs text-emerald-400 font-semibold tracking-wider uppercase">Rakyat Mandiri</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto text-sm">
                <?php if(in_array(Auth::user()->role, ['direktur', 'bendahara'])): ?>
                <!-- Dashboard Utama (Hanya Ketua & Bendahara) -->
                <a href="<?php echo e(route('dashboard')); ?>"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-lg font-medium transition <?php echo e(request()->routeIs('dashboard') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="bi bi-grid-1x2-fill text-lg"></i>
                    <span>Dashboard Utama</span>
                </a>

                <!-- SECTION: UNIT USAHA -->
                <div class="pt-4 pb-1 px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">
                    Unit Usaha BUMDes
                </div>

                <!-- CFD & UMKM -->
                <a href="<?php echo e(route('cfd.index')); ?>"
                    class="flex items-center justify-between px-3 py-2.5 rounded-lg font-medium transition <?php echo e(request()->routeIs('cfd.*') ? 'bg-rose-700/80 text-white border-l-4 border-rose-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                    <div class="flex items-center space-x-3">
                        <span class="w-7 h-7 rounded-lg bg-rose-500/20 text-rose-400 flex items-center justify-center">
                            <i class="bi bi-shop"></i>
                        </span>
                        <span>CFD & Pasar UMKM</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded bg-rose-500/20 text-rose-300">Minggu</span>
                </a>

                <!-- Pamsimas -->
                <a href="<?php echo e(route('pamsimas.index')); ?>"
                    class="flex items-center justify-between px-3 py-2.5 rounded-lg font-medium transition <?php echo e(request()->routeIs('pamsimas.*') ? 'bg-sky-700/80 text-white border-l-4 border-sky-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                    <div class="flex items-center space-x-3">
                        <span class="w-7 h-7 rounded-lg bg-sky-500/20 text-sky-400 flex items-center justify-center">
                            <i class="bi bi-droplet-half"></i>
                        </span>
                        <span>Pamsimas Air Bersih</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded bg-sky-500/20 text-sky-300">Air</span>
                </a>

                <!-- SECTION: KEUANGAN TERPADU -->
                <div class="pt-5 pb-1 px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">
                    Keuangan & Pembukuan
                </div>

                <!-- Buku Kas Umum -->
                <a href="<?php echo e(route('finance.transactions')); ?>"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-lg font-medium transition <?php echo e(request()->routeIs('finance.transactions*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="bi bi-journal-text text-lg"></i>
                    <span>Buku Kas Umum (BKU)</span>
                </a>

                <!-- Laporan Keuangan -->
                <a href="<?php echo e(route('finance.reports')); ?>"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-lg font-medium transition <?php echo e(request()->routeIs('finance.reports*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="bi bi-file-earmark-spreadsheet text-lg"></i>
                    <span>Laporan Keuangan</span>
                </a>

                <?php else: ?>
                <!-- JIKA LOGIN SEBAGAI ADMIN UNIT (HANYA UNITNYA SENDIRI YANG MUNCUL) -->
                <div class="pt-2 pb-1 px-3 text-xs font-bold text-emerald-400 uppercase tracking-wider flex items-center space-x-1.5">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>Unit Usaha Anda</span>
                </div>

                <?php if(Auth::user()->unit_code === 'cfd'): ?>
                <a href="<?php echo e(route('cfd.index')); ?>"
                    class="flex items-center justify-between px-3 py-2.5 rounded-lg font-medium transition bg-rose-700 text-white shadow-md">
                    <div class="flex items-center space-x-3">
                        <span class="w-7 h-7 rounded-lg bg-rose-500/20 text-rose-300 flex items-center justify-center">
                            <i class="bi bi-shop"></i>
                        </span>
                        <span>Unit CFD & Pasar UMKM</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded bg-rose-500/30 text-rose-200">CFD</span>
                </a>
                <?php elseif(Auth::user()->unit_code === 'pamsimas'): ?>
                <a href="<?php echo e(route('pamsimas.index')); ?>"
                    class="flex items-center justify-between px-3 py-2.5 rounded-lg font-medium transition bg-sky-700 text-white shadow-md">
                    <div class="flex items-center space-x-3">
                        <span class="w-7 h-7 rounded-lg bg-sky-500/20 text-sky-300 flex items-center justify-center">
                            <i class="bi bi-droplet-half"></i>
                        </span>
                        <span>Unit Pamsimas Air Bersih</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded bg-sky-500/30 text-sky-200">Air</span>
                </a>
                <?php endif; ?>

                <!-- Pembukuan Unit -->
                <div class="pt-5 pb-1 px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">
                    Pembukuan Unit
                </div>

                <a href="<?php echo e(route('finance.transactions')); ?>"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-lg font-medium transition <?php echo e(request()->routeIs('finance.transactions*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="bi bi-journal-text text-lg"></i>
                    <span>Buku Kas <?php echo e(strtoupper(Auth::user()->unit_code)); ?></span>
                </a>

                <a href="<?php echo e(route('finance.reports')); ?>"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-lg font-medium transition <?php echo e(request()->routeIs('finance.reports*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="bi bi-file-earmark-spreadsheet text-lg"></i>
                    <span>Laporan Keuangan Unit</span>
                </a>
                <?php endif; ?>
            </nav>

            <!-- User Footer Profile & Role -->
            <div class="p-3 bg-slate-950/70 border-t border-slate-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2.5 overflow-hidden">
                        <div class="w-9 h-9 rounded-full bg-emerald-700 text-white flex items-center justify-center font-bold text-sm shrink-0">
                            <?php echo e(substr(Auth::user()->name ?? 'U', 0, 1)); ?>

                        </div>
                        <div class="truncate">
                            <p class="text-xs font-semibold text-white truncate"><?php echo e(Auth::user()->name ?? 'Pengguna'); ?></p>
                            <span class="inline-block px-1.5 py-0.5 rounded text-[10px] uppercase font-bold 
                                <?php echo e(Auth::user()->role === 'direktur' ? 'bg-purple-900/60 text-purple-300' : (Auth::user()->role === 'bendahara' ? 'bg-amber-900/60 text-amber-300' : 'bg-emerald-900/60 text-emerald-300')); ?>">
                                <?php echo e(Auth::user()->role === 'admin_unit' ? 'Admin ' . strtoupper(Auth::user()->unit_code) : (Auth::user()->role === 'direktur' ? 'Ketua' : ucfirst(Auth::user()->role))); ?>

                            </span>
                        </div>
                    </div>
                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" title="Keluar / Logout" class="text-slate-400 hover:text-rose-400 p-1.5 rounded-lg hover:bg-slate-800 transition">
                            <i class="bi bi-box-arrow-right text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- TOP NAVBAR -->
            <header class="no-print bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
                <div class="px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 focus:outline-none">
                            <i class="bi bi-list text-2xl"></i>
                        </button>
                        <div class="hidden sm:block">
                            <span class="text-xs text-slate-400 font-medium">Sistem Informasi Laporan Keuangan BUMDes</span>
                            <?php if(Auth::user()->role === 'admin_unit'): ?>
                            <h2 class="text-sm font-bold text-slate-800 flex items-center space-x-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Halaman Khusus: <?php echo e(Auth::user()->unit_name); ?></span>
                            </h2>
                            <?php else: ?>
                            <h2 class="text-sm font-bold text-slate-800">BUMDes "Rakyat Mandiri" - CFD & Pamsimas</h2>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Right Controls: Date -->
                    <div class="flex items-center space-x-3">
                        <!-- Date Badge -->
                        <div class="hidden lg:flex items-center space-x-1.5 text-xs bg-emerald-50 text-emerald-800 px-3 py-2 rounded-lg font-medium border border-emerald-200">
                            <i class="bi bi-calendar-event"></i>
                            <span><?php echo e(\Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y')); ?></span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- FLASH ALERTS -->
            <div class="no-print px-4 sm:px-6 lg:px-8 pt-4">
                <?php if(session('success')): ?>
                <div class="mb-4 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between text-emerald-800"
                    x-data="{ show: true }" x-show="show">
                    <div class="flex items-center space-x-3">
                        <i class="bi bi-check-circle-fill text-xl text-emerald-500"></i>
                        <span class="text-sm font-medium"><?php echo e(session('success')); ?></span>
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-900">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                <div class="mb-4 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between text-rose-800"
                    x-data="{ show: true }" x-show="show">
                    <div class="flex items-center space-x-3">
                        <i class="bi bi-exclamation-triangle-fill text-xl text-rose-500"></i>
                        <span class="text-sm font-medium"><?php echo e(session('error')); ?></span>
                    </div>
                    <button @click="show = false" class="text-rose-600 hover:text-rose-900">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                <div class="mb-4 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm text-rose-800">
                    <div class="flex items-center space-x-2 font-bold mb-1">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <span>Terdapat kesalahan pengisian data:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-0.5">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>

            <!-- MAIN CONTENT BODY -->
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
                <?php echo $__env->yieldContent('content'); ?>
            </main>

            <!-- FOOTER -->
            <footer class="no-print bg-white border-t border-slate-200 py-4 px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-500">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
                    <p>&copy; <?php echo e(date('Y')); ?> <strong>BUMDes Rakyat Mandiri</strong>. Sistem Informasi Keuangan CFD & Pamsimas.</p>
                    <div class="flex items-center space-x-3 text-slate-400">
                        <span>CFD</span> &bull;
                        <span>Pamsimas</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Backdrop for mobile sidebar -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
        class="no-print fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden transition-opacity">
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH D:\BumdesAntigravity\resources\views/layouts/app.blade.php ENDPATH**/ ?>