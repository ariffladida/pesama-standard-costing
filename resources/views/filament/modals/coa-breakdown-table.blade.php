<div class="overflow-x-auto p-1 max-h-[65vh]">
    <table class="w-full text-xs text-left border-collapse border border-slate-700">
        <thead class="sticky top-0 bg-slate-800 text-slate-200 z-10">
            <tr>
                <th class="border border-slate-700 p-2.5">Kod COA</th>
                <th class="border border-slate-700 p-2.5">Keterangan Akaun</th>
                <th class="border border-slate-700 p-2.5 text-center">Unit ID</th>
                <th class="border border-slate-700 p-2.5 text-center">Asas</th>
                <th class="border border-slate-700 p-2.5 text-right">Kadar Std / Tan (RM)</th>
                <th class="border border-slate-700 p-2.5 text-center">Status Fleksibiliti</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800 font-sans">
            @forelse($coas as $coa)
                @if($coa->cost_type === 'Summary')
                    <tr class="bg-slate-900/90 font-bold text-emerald-400 border-t-2 border-slate-700">
                        <td class="p-2 border border-slate-800 font-mono">{{ $coa->coa_code }}</td>
                        <td colspan="3" class="p-2 border border-slate-800 tracking-wide uppercase">{{ $coa->name }}</td>
                        <td class="p-2 border border-slate-800 text-right font-mono">--</td>
                        <td class="p-2 border border-slate-800 text-center text-slate-500">Header</td>
                    </tr>
                @else
                    <tr class="hover:bg-slate-800/40 text-slate-300">
                        <td class="p-2 border border-slate-800 font-mono text-emerald-400 font-medium">{{ $coa->coa_code }}</td>
                        <td class="p-2 border border-slate-800">{{ $coa->name }}</td>
                        <td class="p-2 border border-slate-800 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $coa->cost_type === 'Fixed' ? 'bg-blue-900/60 text-blue-300' : 'bg-amber-900/60 text-amber-300' }}">
                                {{ $coa->cost_type }}
                            </span>
                        </td>
                        <td class="p-2 border border-slate-800 text-center text-slate-400">{{ $coa->basis_type }}</td>
                        <td class="p-2 border border-slate-800 text-right font-mono text-white">
                            RM {{ number_format($coa->standard_rate_per_ton, 2) }}
                        </td>
                        <td class="p-2 border border-slate-800 text-center">
                            @if($coa->is_reducible)
                                <span class="text-emerald-400 font-semibold">✓ Fleksibel</span>
                            @else
                                <span class="text-rose-400 font-semibold">✕ Terikat Kontrak</span>
                            @endif
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-slate-400">Tiada data COA dijumpai. Sila jalankan seeder.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot class="sticky bottom-0 bg-slate-900 font-bold text-white border-t-2 border-slate-600">
            <tr>
                <td colspan="4" class="p-2.5 text-right text-slate-300">JUMLAH KOS PEMBUATAN (BASE MFG):</td>
                <td class="p-2.5 text-right font-mono text-emerald-400 text-sm">
                    RM {{ number_format($coas->whereNotIn('cost_type', ['Summary', 'Balance'])->sum('standard_rate_per_ton'), 2) }}
                </td>
                <td class="p-2.5"></td>
            </tr>
        </tfoot>
    </table>
</div>