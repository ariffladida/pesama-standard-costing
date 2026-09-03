<div class="mb-6 rounded-xl border border-slate-700/60 bg-slate-900/90 p-6 text-white shadow-xl">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-800 pb-4">
        <div>
            <h1 class="text-xl font-extrabold tracking-tight text-emerald-400 uppercase">
                {{ $company }}
            </h1>
            <p class="text-sm text-slate-300 font-medium">
                {{ $title }} &mdash; <span class="text-amber-400 font-semibold">{{ $plant }}</span>
            </p>
        </div>
        <div class="mt-3 md:mt-0 flex gap-4 text-xs">
            <div class="bg-slate-800/80 px-3 py-2 rounded-lg border border-slate-700">
                <span class="text-slate-400 block">Std Capacity (Moulding):</span>
                <span class="text-white font-bold text-sm">{{ $capacityMoulding }}</span>
            </div>
            <div class="bg-slate-800/80 px-3 py-2 rounded-lg border border-slate-700">
                <span class="text-slate-400 block">Std Capacity (FJ):</span>
                <span class="text-white font-bold text-sm">{{ $capacityFj }}</span>
            </div>
        </div>
    </div>

    <div class="mt-4 flex flex-wrap items-center justify-between gap-4 text-sm">
        <div class="text-slate-400">
            Format Struktur Dokumen: <span class="text-slate-200 font-semibold">1:1 Helaian Rasmi Akaun Kilang</span>
        </div>
        <div class="flex gap-6">
            <div>
                <span class="text-slate-400 text-xs uppercase block">Jumlah Kos / Tan</span>
                <span class="text-lg font-mono font-bold text-emerald-400">RM {{ $totalRate }}</span>
            </div>
            <div>
                <span class="text-slate-400 text-xs uppercase block">Jumlah Kos Operasi</span>
                <span class="text-lg font-mono font-bold text-amber-400">RM {{ $totalCost }}</span>
            </div>
        </div>
    </div>
</div>