<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Standard Costing Framework | Pesama Timber</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between selection:bg-amber-500 selection:text-black">

    <!-- Header / Navbar -->
    <header class="border-b border-slate-800/80 backdrop-blur-md sticky top-0 z-50 bg-slate-950/70">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-500 font-bold text-lg">
                    ST
                </div>
                <div>
                    <h1 class="text-sm font-semibold tracking-wider uppercase text-slate-200">Standard Costing System</h1>
                    <p class="text-xs text-slate-400">Pesama Timber Corporation</p>
                </div>
            </div>
            <div>
                <a href="/admin" class="px-5 py-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider bg-amber-500 hover:bg-amber-400 text-slate-950 transition duration-200 shadow-lg shadow-amber-500/10">
                    Masuk Portal Sistem &rarr;
                </a>
            </div>
        </div>
    </header>

    <!-- Hero & Introduction -->
    <main class="max-w-7xl mx-auto px-6 py-16 flex-grow flex flex-col justify-center">
        <div class="max-w-3xl mb-14">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-900 border border-slate-800 text-amber-400 text-xs font-medium mb-4">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                Fasa Prototaip Pengiraan Kos
            </div>
            <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-white leading-tight">
                Rangka Kerja Standard Costing & Pengiraan Kos Produk Kayu
            </h2>
            <p class="mt-4 text-base text-slate-400 leading-relaxed">
                Platform berpusat untuk menetapkan kos standard per tan merentasi produk utama. Pengiraan kos bersepadu meliputi kos balak, kos pengangkutan tetap tahunan, kos pembuatan, serta pecahan gred dan saiz.
            </p>
        </div>

        <!-- 3 Modul Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Modul 1: Sawn Timber -->
            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-7 flex flex-col justify-between hover:border-slate-700 transition duration-300">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center mb-5 font-semibold text-lg">
                        01
                    </div>
                    <h3 class="text-lg font-semibold text-white">Sawn Timber (ST)</h3>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                        Pengiraan purata kos pembuatan per tan (gaji & perbelanjaan) digabungkan dengan Log Cost mengikut spesies dan pecahan kos kepada 20–30 gred akhir.
                    </p>
                    <div class="mt-4 space-y-1.5 text-xs text-slate-300">
                        <p class="flex items-center gap-2 text-slate-400">
                            <span class="text-blue-400 font-bold">&#x2713;</span> Log Cost dinamik ikut spesies
                        </p>
                        <p class="flex items-center gap-2 text-slate-400">
                            <span class="text-blue-400 font-bold">&#x2713;</span> Kos Pengangkutan Tetap (RM68/tan)
                        </p>
                        <p class="flex items-center gap-2 text-slate-400">
                            <span class="text-blue-400 font-bold">&#x2713;</span> Pecahan mengikut gred kualiti
                        </p>
                    </div>
                </div>
                <div class="mt-8 pt-4 border-t border-slate-800/80">
                    <a href="/admin/st-costings" class="text-xs font-semibold text-blue-400 hover:text-blue-300 flex items-center justify-between group">
                        Buka Modul ST <span class="group-hover:translate-x-1 transition duration-150">&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Modul 2: Moulding -->
            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-7 flex flex-col justify-between hover:border-slate-700 transition duration-300">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center mb-5 font-semibold text-lg">
                        02
                    </div>
                    <h3 class="text-lg font-semibold text-white">Moulding (Kumai)</h3>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                        Penetapan kos kumai berasaskan dua punca bahan mentah utama bagi menghasilkan saiz standard 28mm x 133mm dan 28mm x 145mm.
                    </p>
                    <div class="mt-4 space-y-1.5 text-xs text-slate-300">
                        <p class="flex items-center gap-2 text-slate-400">
                            <span class="text-emerald-400 font-bold">&#x2713;</span> Sumber: Process & Purchase Luar
                        </p>
                        <p class="flex items-center gap-2 text-slate-400">
                            <span class="text-emerald-400 font-bold">&#x2713;</span> Input manual kos bahan mentah
                        </p>
                        <p class="flex items-center gap-2 text-slate-400">
                            <span class="text-emerald-400 font-bold">&#x2713;</span> Pemetaan 2 saiz produk akhir
                        </p>
                    </div>
                </div>
                <div class="mt-8 pt-4 border-t border-slate-800/80">
                    <a href="/admin/moulding-costings" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 flex items-center justify-between group">
                        Buka Modul Moulding <span class="group-hover:translate-x-1 transition duration-150">&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Modul 3: Finger Joint -->
            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-7 flex flex-col justify-between hover:border-slate-700 transition duration-300">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center mb-5 font-semibold text-lg">
                        03
                    </div>
                    <h3 class="text-lg font-semibold text-white">Finger Joint (FJ)</h3>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                        Sistem pengiraan sambungan jejari mengikut pelbagai saiz akhir dengan pengasingan logik kos bahan bagi kategori Off-Cut.
                    </p>
                    <div class="mt-4 space-y-1.5 text-xs text-slate-300">
                        <p class="flex items-center gap-2 text-slate-400">
                            <span class="text-amber-400 font-bold">&#x2713;</span> Sumber: Process, Purchase & Off Cut
                        </p>
                        <p class="flex items-center gap-2 text-slate-400">
                            <span class="text-amber-400 font-bold">&#x2713;</span> Logik Automatik Off Cut (Bahan RM0)
                        </p>
                        <p class="flex items-center gap-2 text-slate-400">
                            <span class="text-amber-400 font-bold">&#x2713;</span> Saiz fleksibel (21x44, 19x43, dll.)
                        </p>
                    </div>
                </div>
                <div class="mt-8 pt-4 border-t border-slate-800/80">
                    <a href="/admin/fj-costings" class="text-xs font-semibold text-amber-400 hover:text-amber-300 flex items-center justify-between group">
                        Buka Modul FJ <span class="group-hover:translate-x-1 transition duration-150">&rarr;</span>
                    </a>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} ARIFF HAKIMI. Standard Costing Prototype.
    </footer>

</body>
</html>