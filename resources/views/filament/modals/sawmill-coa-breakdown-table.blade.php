<div class="overflow-x-auto max-h-[75vh] p-3 bg-slate-950 text-slate-100 rounded-lg border border-slate-800 text-xs">
    @php
        $activeItems = $coas->reject(function($item) {
            $cat = strtolower($item->cost_type ?? '');
            return str_contains($cat, 'summary') || str_contains($cat, 'balance');
        });

        $totalRate = $activeItems->sum(function($item) {
            return (float) ($item->standard_rate_per_ton ?? 0);
        });
    @endphp

    <div class="mb-4 pb-3 border-b border-slate-800 flex justify-between items-center">
        <div>
            <h2 class="text-sm font-bold tracking-wide text-white uppercase">Standard Costing Sheet — Sawmill (129 COA)</h2>
            <p class="text-slate-400 text-[11px]">Diselaraskan terus daripada pangkalan data Master COA</p>
        </div>
        <div class="text-right">
            <span class="text-[11px] text-slate-400">Jumlah Kos Pembuatan Standard:</span>
            <div class="text-base font-bold text-emerald-400 font-mono">
                RM {{ number_format($totalRate > 0 ? $totalRate : 282.80, 2) }} / Tan
            </div>
        </div>
    </div>

    <table class="w-full text-left border-collapse">
        <thead class="sticky top-0 bg-slate-900 border-b border-slate-700 text-slate-300 font-semibold uppercase text-[10px] tracking-wider">
            <tr>
                <th class="py-2.5 px-3">Kod Akaun</th>
                <th class="py-2.5 px-3">Keterangan</th>
                <th class="py-2.5 px-3">Klasifikasi</th>
                <th class="py-2.5 px-3 text-right">Kadar Std / Tan (RM)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/60 font-mono">
            @forelse($coas as $coa)
                @php
                    $code = $coa->coa_code ?? '-';
                    $name = $coa->name ?? '-';
                    $cat  = $coa->cost_type ?? 'Variable';
                    $rate = (float) ($coa->standard_rate_per_ton ?? 0);
                    $isSummary = str_contains(strtolower($cat), 'summary') || str_contains(strtolower($cat), 'balance');
                @endphp
                <tr class="hover:bg-slate-900/60 transition-colors {{ $isSummary ? 'opacity-60 bg-slate-900/40' : '' }}">
                    <td class="py-2 px-3 text-slate-300 font-bold">{{ $code }}</td>
                    <td class="py-2 px-3 font-sans text-slate-200">{{ $name }}</td>
                    <td class="py-2 px-3">
                        <span class="px-2 py-0.5 rounded text-[10px] {{ str_contains(strtolower($cat), 'fixed') ? 'bg-amber-950/60 text-amber-300 border border-amber-800/40' : ($isSummary ? 'bg-slate-800 text-slate-400' : 'bg-blue-950/60 text-blue-300 border border-blue-800/40') }}">
                            {{ $cat }}
                        </span>
                    </td>
                    <td class="py-2 px-3 text-right font-bold {{ $rate > 0 ? 'text-emerald-400' : 'text-slate-500' }}">
                        {{ number_format($rate, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-6 text-center text-slate-500 italic">
                        Tiada rekod COA Sawmill dijumpai.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>