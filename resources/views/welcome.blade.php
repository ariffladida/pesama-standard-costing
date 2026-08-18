<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Timber Product Costing Engine</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between selection:bg-emerald-500 selection:text-white">

    <!-- Header / Navbar -->
    <header class="border-b border-slate-800/80 backdrop-blur-md sticky top-0 z-50 bg-slate-950/80">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-400 flex items-center justify-center font-bold text-white shadow-lg shadow-emerald-900/40">
                    TPC
                </div>
                <div>
                    <span class="text-lg font-bold tracking-tight text-white block leading-tight">TIMBER PRODUCT COSTING</span>
                    <span class="text-xs text-slate-400 font-medium tracking-wide">Multi-Subsidiary Costing Engine</span>
                </div>
            </div>
            <a href="/admin" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl font-semibold text-sm text-slate-950 bg-emerald-400 hover:bg-emerald-300 shadow-md shadow-emerald-500/20 transition duration-200">
                Akses Portal &rarr;
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-16 flex-1 flex flex-col justify-center">
        <!-- Hero Section -->
        <div class="max-w-3xl mb-16">
            <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold uppercase tracking-wider mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Standard Costing Engine v2.0</span>
            </div>
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-white leading-tight mb-5">
                Pengiraan Kos Standard <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400">Produk Berasaskan Kayu</span>
            </h1>
            <p class="text-base sm:text-lg text-slate-400 leading-relaxed">
                Platform berpusat pengiraan kos standard per tan merentasi produk utama. Pengiraan kos bersepadu meliputi kos balak, logistik pengangkutan, pemprosesan kilang, dan penetapan pecahan kos mengikut gred spesifik.
            </p>
        </div>

        <!-- 3 Product Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Sawn Timber Card -->
            <div class="relative group bg-slate-900/60 border border-slate-800 rounded-2xl p-7 hover:border-emerald-500/40 hover:bg-slate-900 transition-all duration-300 shadow-xl flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <span class="text-xs font-bold px-3 py-1 bg-slate-800 text-emerald-400 border border-emerald-500/20 rounded-lg">01</span>
                        <span class="text-xs text-slate-500 uppercase tracking-widest font-semibold">ST Series</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Sawn Timber</h3>
                    <p class="text-sm text-slate-400 mb-6 leading-relaxed">
                        Penetapan purata kos pembuatan digabungkan dengan Log Cost dinamik mengikut spesies serta pecahan kos kepada 20–30 gred akhir.
                    </p>
                    <ul class="text-xs text-slate-300 space-y-2.5 mb-6">
                        <li class="flex items-center text-slate-300"><svg class="w-4 h-4 text-emerald-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Log Cost dinamik ikut spesies</li>
                        <li class="flex items-center text-slate-300"><svg class="w-4 h-4 text-emerald-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Kos Pengangkutan Tetap Tahunan</li>
                        <li class="flex items-center text-slate-300"><svg class="w-4 h-4 text-emerald-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Pecahan kos mengikut gred kualiti</li>
                    </ul>
                </div>
                <a href="/admin/sawn-timber-costings" class="inline-flex items-center text-xs font-semibold text-emerald-400 group-hover:text-emerald-300 group-hover:translate-x-1 transition-all duration-200">
                    Buka Modul ST &rarr;
                </a>
            </div>

            <!-- Moulding Card -->
            <div class="relative group bg-slate-900/60 border border-slate-800 rounded-2xl p-7 hover:border-teal-500/40 hover:bg-slate-900 transition-all duration-300 shadow-xl flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <span class="text-xs font-bold px-3 py-1 bg-slate-800 text-teal-400 border border-teal-500/20 rounded-lg">02</span>
                        <span class="text-xs text-slate-500 uppercase tracking-widest font-semibold">Moulding</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Moulding (Kumai)</h3>
                    <p class="text-sm text-slate-400 mb-6 leading-relaxed">
                        Penetapan kos kumai berasaskan sumber bahan mentah (Process & Purchase Luar) bagi saiz standard pasaran.
                    </p>
                    <ul class="text-xs text-slate-300 space-y-2.5 mb-6">
                        <li class="flex items-center text-slate-300"><svg class="w-4 h-4 text-teal-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Sumber: Process & Purchase Luar</li>
                        <li class="flex items-center text-slate-300"><svg class="w-4 h-4 text-teal-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Struktur saiz 28mm x 133mm & 145mm</li>
                        <li class="flex items-center text-slate-300"><svg class="w-4 h-4 text-teal-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Pemetaan kos bahan & upah kerja</li>
                    </ul>
                </div>
                <a href="/admin/moulding-costings" class="inline-flex items-center text-xs font-semibold text-teal-400 group-hover:text-teal-300 group-hover:translate-x-1 transition-all duration-200">
                    Buka Modul Moulding &rarr;
                </a>
            </div>

            <!-- Finger Joint Card -->
            <div class="relative group bg-slate-900/60 border border-slate-800 rounded-2xl p-7 hover:border-cyan-500/40 hover:bg-slate-900 transition-all duration-300 shadow-xl flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <span class="text-xs font-bold px-3 py-1 bg-slate-800 text-cyan-400 border border-cyan-500/20 rounded-lg">03</span>
                        <span class="text-xs text-slate-500 uppercase tracking-widest font-semibold">FJ Series</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Finger Joint (FJ)</h3>
                    <p class="text-sm text-slate-400 mb-6 leading-relaxed">
                        Sistem pengiraan sambungan jejari mengikut pelbagai saiz akhir dengan pengasingan logik bahan Off-Cut (RM0).
                    </p>
                    <ul class="text-xs text-slate-300 space-y-2.5 mb-6">
                        <li class="flex items-center text-slate-300"><svg class="w-4 h-4 text-cyan-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Sumber: Process, Purchase & Off-Cut</li>
                        <li class="flex items-center text-slate-300"><svg class="w-4 h-4 text-cyan-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Auto-assign RM0 bagi Off-Cut</li>
                        <li class="flex items-center text-slate-300"><svg class="w-4 h-4 text-cyan-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Pengiraan saiz 21x44, 19x43, dll.</li>
                    </ul>
                </div>
                <a href="/admin/finger-joint-costings" class="inline-flex items-center text-xs font-semibold text-cyan-400 group-hover:text-cyan-300 group-hover:translate-x-1 transition-all duration-200">
                    Buka Modul Finger Joint &rarr;
                </a>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 py-6 text-center text-xs text-slate-400 font-medium">
        Timber Product Costing Engine &copy; 2026. All rights reserved.
    </footer>

</body>
</html>