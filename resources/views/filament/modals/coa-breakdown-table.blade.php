<div class="space-y-4">
    <!-- Pengepala Rasmi Mengikut Helaian Excel -->
    <div class="rounded-lg border border-slate-700/80 bg-slate-900/90 p-4 text-white shadow">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-800 pb-3 gap-2">
            <div>
                <h3 class="text-base font-bold text-emerald-400 tracking-wide uppercase">
                    Pesama Timber Corporation Sdn. Bhd.
                </h3>
                <p class="text-xs text-slate-300">
                    Standard Costing Computation &mdash; <span class="text-amber-400 font-semibold">Moulding & FJ</span>
                </p>
            </div>
            <div class="flex gap-3 text-xs">
                <div class="bg-slate-800 px-2.5 py-1.5 rounded border border-slate-700">
                    <span class="text-slate-400 block text-[10px] uppercase">Std Capacity:</span>
                    <span class="text-emerald-300 font-bold">150 Ton</span>
                </div>
                <div class="bg-slate-800 px-2.5 py-1.5 rounded border border-slate-700">
                    <span class="text-slate-400 block text-[10px] uppercase">Jumlah Kos/Tan:</span>
                    <span class="text-amber-300 font-mono font-bold">RM 346.37</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadual 5-Lajur 1:1 Mengikut Excel -->
    <div class="overflow-x-auto rounded-lg border border-slate-700 bg-slate-950 shadow max-h-[60vh] overflow-y-auto">
        <table class="w-full text-left text-xs border-collapse">
            <thead class="sticky top-0 z-10 bg-slate-900 text-slate-300 uppercase tracking-wider font-semibold border-b border-slate-700">
                <tr>
                    <th scope="col" class="py-2.5 px-3 w-28 border-r border-slate-800">Acc. No.</th>
                    <th scope="col" class="py-2.5 px-3 border-r border-slate-800">Description</th>
                    <th scope="col" class="py-2.5 px-3 w-40 border-r border-slate-800 text-center">Category</th>
                    <th scope="col" class="py-2.5 px-3 w-32 border-r border-slate-800 text-right">Cost/ton (RM)</th>
                    <th scope="col" class="py-2.5 px-3 w-32 text-right">Total cost (RM)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 font-mono">
                @foreach($coas as $item)
                    @php
                        $isHeader = empty($item->cost_type) && str_contains($item->coa_code, '/000') && !str_contains($item->coa_code, '7700');
                        $isTotal = str_contains($item->coa_code, 'ZZ') || str_contains($item->coa_code, 'Z9');
                        $rate = (float) $item->standard_rate_per_ton;
                        $total = (float) $item->total_cost;
                    @endphp

                    @if($isHeader)
                        <!-- Baris Tajuk Seksyen (MANUFACTURING A/C, OPERATION COST, OVERHEAD) -->
                        <tr class="bg-slate-900 font-sans font-bold text-white tracking-wide border-t-2 border-b border-slate-700">
                            <td class="py-2 px-3 text-amber-300 border-r border-slate-800">{{ $item->coa_code }}</td>
                            <td colspan="4" class="py-2 px-3 text-amber-200 uppercase tracking-wider">{{ $item->name }}</td>
                        </tr>
                    @elseif($isTotal)
                        <!-- Baris Subtotal / Total Seksyen -->
                        <tr class="bg-slate-800/90 font-sans font-bold text-emerald-300 border-t border-b-2 border-slate-600">
                            <td class="py-2 px-3 border-r border-slate-700">{{ $item->coa_code }}</td>
                            <td class="py-2 px-3 uppercase tracking-wider border-r border-slate-700">{{ $item->name }}</td>
                            <td class="py-2 px-3 text-center border-r border-slate-700 font-mono text-[11px] text-slate-400">SUBTOTAL</td>
                            <td class="py-2 px-3 text-right font-mono border-r border-slate-700">{{ $rate > 0 ? number_format($rate, 2) : '--' }}</td>
                            <td class="py-2 px-3 text-right font-mono text-emerald-400">{{ $total > 0 ? number_format($total, 2) : '0.00' }}</td>
                        </tr>
                    @else
                        <!-- Baris Item Standard -->
                        <tr class="hover:bg-slate-900/50 transition-colors {{ $rate > 0 ? 'bg-emerald-950/10' : '' }}">
                            <td class="py-1.5 px-3 font-semibold text-slate-300 border-r border-slate-800/80">{{ $item->coa_code }}</td>
                            <td class="py-1.5 px-3 font-sans text-slate-200 border-r border-slate-800/80">{{ $item->name }}</td>
                            <td class="py-1.5 px-3 text-center border-r border-slate-800/80 font-sans">
                                @if($item->cost_type === 'Variable overhead')
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-500/10 text-amber-300 border border-amber-500/30">Variable overhead</span>
                                @elseif($item->cost_type === 'Fixed overhead')
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-sky-500/10 text-sky-300 border border-sky-500/30">Fixed overhead</span>
                                @elseif($item->cost_type === 'Raw material')
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-300 border border-emerald-500/30">Raw material</span>
                                @elseif($item->cost_type === 'Stock')
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-500/10 text-rose-300 border border-rose-500/30">Stock</span>
                                @else
                                    <span class="text-slate-500">--</span>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 text-right border-r border-slate-800/80 {{ $rate > 0 ? 'font-bold text-emerald-400' : 'text-slate-500' }}">
                                {{ $rate > 0 ? number_format($rate, 2) : '--' }}
                            </td>
                            <td class="py-1.5 px-3 text-right {{ $total > 0 ? 'font-bold text-slate-200' : 'text-slate-600' }}">
                                {{ $total > 0 ? number_format($total, 2) : '0.00' }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>