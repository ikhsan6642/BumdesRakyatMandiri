<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sistem Informasi Keuangan BUMDes Rakyat Mandiri</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('logo-bumdes.jpeg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-950 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-4xl w-full grid grid-cols-1 lg:grid-cols-12 bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-700/20 my-8">
        <!-- LEFT: BRAND & DEMO ACCOUNTS -->
        <div class="lg:col-span-5 bg-gradient-to-b from-emerald-800 to-slate-900 p-8 text-white flex flex-col justify-between">
            <div>
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-16 h-16 shrink-0 rounded-full bg-white p-1 ring-2 ring-emerald-300/70 shadow-lg shadow-emerald-950/30">
                        <img id="bumdes-logo" src="{{ asset('logo-bumdes.jpeg') }}" alt="Logo BUMDes Rakyat Mandiri" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold tracking-wide">BUMDes</h1>
                        <p class="text-xs text-emerald-300 font-semibold uppercase tracking-wider">Rakyat Mandiri</p>
                    </div>
                </div>

                <h2 class="text-2xl font-bold leading-snug mb-3">Sistem Informasi Laporan Keuangan</h2>
                <p class="text-xs text-emerald-100/80 mb-6 leading-relaxed">
                    Platform pembukuan dan manajemen operasional BUMDes untuk unit CFD dan Pamsimas.
                </p>

                <!-- Unit Pills -->
                <div class="space-y-2 mb-6">
                    <div class="flex items-center space-x-2.5 text-xs bg-white/10 backdrop-blur-sm px-3 py-2 rounded-xl border border-white/10">
                        <i class="bi bi-shop text-rose-400"></i>
                        <span>Unit CFD & Pasar UMKM Minggu</span>
                    </div>
                    <div class="flex items-center space-x-2.5 text-xs bg-white/10 backdrop-blur-sm px-3 py-2 rounded-xl border border-white/10">
                        <i class="bi bi-droplet-half text-sky-400"></i>
                        <span>Unit Pamsimas Air Bersih</span>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-white/10 text-[11px] text-emerald-200/60 flex items-center justify-between">
                <span>Versi 1.0 &bull; Laravel 12</span>
                <span>Standar Kemendesa</span>
            </div>
        </div>

        <!-- RIGHT: LOGIN FORM -->
        <div class="lg:col-span-7 p-8 sm:p-10 flex flex-col justify-center">
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-slate-800">Masuk ke Sistem</h3>
                <p class="text-xs text-slate-500 mt-1">Masuk menggunakan email dan password akun Anda.</p>
            </div>

            @if(session('info'))
                <div class="mb-4 bg-sky-50 text-sky-800 text-xs p-3 rounded-xl border border-sky-200 flex items-center space-x-2">
                    <i class="bi bi-info-circle-fill text-sky-600"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-rose-50 text-rose-800 text-xs p-3 rounded-xl border border-rose-200 flex items-center space-x-2">
                    <i class="bi bi-exclamation-triangle-fill text-rose-600"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Standard Form -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email', 'direktur@bumdes.desa.id') }}" required
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition"
                               placeholder="nama@bumdes.desa.id">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-lock"></i>
                        </div>
                        <input type="password" name="password" value="password" required
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center space-x-2 text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-emerald-600 focus:ring-emerald-500">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                    <span class="text-slate-400">Default: password</span>
                </div>

                <button type="submit" 
                        class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-emerald-900/20 transition flex items-center justify-center space-x-2">
                    <i class="bi bi-box-arrow-in-right text-lg"></i>
                    <span>Masuk ke Dashboard BUMDes</span>
                </button>
            </form>
        </div>
    </div>

</body>
</html>
