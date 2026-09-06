<div class="overflow-x-auto max-h-[75vh] p-2 bg-slate-950 text-slate-100 rounded-lg border border-slate-800 text-xs">
    <div class="mb-4 pb-2 border-b border-slate-800 flex justify-between items-center">
        <div>
            <h2 class="text-sm font-bold tracking-wide text-white uppercase">Standard Costing Sheet — Sawmill (129 Items)</h2>
            <p class="text-slate-400 text-[11px]">Kapasiti Pengeluaran Standard: <strong>1,200 Tan / Bulan</strong></p>
        </div>
        <div class="text-right">
            <span class="text-[11px] text-slate-400">Jumlah Kos Pembuatan Standard:</span>
            <div class="text-base font-bold text-emerald-400">
                RM {{ number_format($coas->whereNotIn('cost_type', ['Summary', 'Balance'])->sum('standard_rate_per_ton'), 2) }} / Tan
            </div>
        </div>
    </div>

    <table class="w-full text-left border-collapse">
        <thead class="sticky top-0 bg-slate-900 border-b border-slate-700 text-slate-300 font-semibold uppercase text-[10px] tracking-wider">
            <tr>
                <th class="py-2.5 px-3">Kod Akaun</th>
                <th class="py-2.5 px-3">Keterangan Perbelanjaan</th>
                <th class="py-2.5 px-3">Kategori</th>
                <th class="py-2.5 px-3 text-right">Standard / Bulan (RM)</th>
                <th class="py-2.5 px-3 text-right">Kadar / Tan (RM)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/60 font-mono">
            @forelse($coas as $coa)
                <tr class="hover:bg-slate-900/60 transition-colors">
                    <td class="py-2 px-3 text-slate-400 font-medium">{{ $coa->account_code ?? $coa->acc_no }}</td>
                    <td class="py-2 px-3 font-sans text-slate-200">{{ $coa->description }}</td>
                    <td class="py-2 px-3">
                        <span class="px-2 py-0.5 rounded text-[10px] {{ str_contains(strtolower($coa->category ?? ''), 'fixed') ? 'bg-amber-950/60 text-amber-300 border border-amber-800/40' : 'bg-blue-950/60 text-blue-300 border border-blue-800/40' }}">
                            {{ $coa->category ?? 'Variable Overhead' }}
                        </span>
                    </td>
                    <td class="py-2 px-3 text-right text-slate-300">
                        {{ number_format($coa->monthly_budget ?? ($coa->standard_rate_per_ton * 1200), 2) }}
                    </td>
                    <td class="py-2 px-3 text-right font-bold text-emerald-400">
                        {{ number_format($coa->standard_rate_per_ton, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-6 text-center text-slate-500 italic">
                        Tiada data akaun Sawmill dijumpai dalam pangkalan data.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>