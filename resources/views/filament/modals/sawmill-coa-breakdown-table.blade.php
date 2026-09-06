<div class="overflow-x-auto max-h-[75vh] p-2 bg-slate-950 text-slate-100 rounded-lg border border-slate-800 text-xs">
    @php
        $totalCost = $coas->sum(function($item) {
            return (float) ($item->standard_rate_per_ton ?? $item->rate_per_ton ?? $item->standard_rate ?? $item->rate ?? 0);
        });
    @endphp

    <div class="mb-4 pb-2 border-b border-slate-800 flex justify-between items-center">
        <div>
            <h2 class="text-sm font-bold tracking-wide text-white uppercase">Standard Costing Sheet — Sawmill (Loji Belah)</h2>
            <p class="text-slate-400 text-[11px]">Senarai Lengkap Kod Akaun Operasi Sawmill</p>
        </div>
        <div class="text-right">
            <span class="text-[11px] text-slate-400">Jumlah Kos Pembuatan Standard:</span>
            <div class="text-base font-bold text-emerald-400">
                RM {{ number_format($totalCost, 2) }} / Tan
            </div>
        </div>
    </div>

    <table class="w-full text-left border-collapse">
        <thead class="sticky top-0 bg-slate-900 border-b border-slate-700 text-slate-300 font-semibold uppercase text-[10px] tracking-wider">
            <tr>
                <th class="py-2.5 px-3">Kod Akaun</th>
                <th class="py-2.5 px-3">Keterangan</th>
                <th class="py-2.5 px-3">Klasifikasi / Kategori</th>
                <th class="py-2.5 px-3 text-right">Kadar Std / Tan (RM)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/60 font-mono">
            @forelse($coas as $coa)
                @php
                    $code = $coa->code ?? $coa->acc_no ?? $coa->account_code ?? $coa->account_no ?? '-';
                    $desc = $coa->description ?? $coa->name ?? $coa->keterangan ?? '-';
                    $category = $coa->category ?? $coa->classification ?? $coa->klasifikasi ?? 'Overhead';
                    $rate = (float) ($coa->standard_rate_per_ton ?? $coa->rate_per_ton ?? $coa->standard_rate ?? $coa->rate ?? 0);
                @endphp
                <tr class="hover:bg-slate-900/60 transition-colors">
                    <td class="py-2 px-3 text-slate-300 font-bold">{{ $code }}</td>
                    <td class="py-2 px-3 font-sans text-slate-200">{{ $desc }}</td>
                    <td class="py-2 px-3">
                        <span class="px-2 py-0.5 rounded text-[10px] {{ str_contains(strtolower($category), 'fixed') ? 'bg-amber-950/60 text-amber-300 border border-amber-800/40' : (str_contains(strtolower($category), 'summary') || str_contains(strtolower($category), 'balance') ? 'bg-slate-800 text-slate-400' : 'bg-blue-950/60 text-blue-300 border border-blue-800/40') }}">
                            {{ $category }}
                        </span>
                    </td>
                    <td class="py-2 px-3 text-right font-bold {{ $rate > 0 ? 'text-emerald-400' : 'text-slate-500' }}">
                        {{ number_format($rate, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-6 text-center text-slate-500 italic">
                        Tiada data akaun Sawmill dijumpai dalam pangkalan data.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>