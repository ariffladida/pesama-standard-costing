<?php

namespace App\Filament\Resources\CoaMouldingFjResource\Pages;

use App\Filament\Resources\CoaMouldingFjResource;
use App\Models\CoaItem;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;

class ListCoaMouldingFjs extends ListRecords
{
    protected static string $resource = CoaMouldingFjResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('+ Tambah Kod Akaun'),
        ];
    }

    public function getHeader(): ?View
    {
        $hasTotalCost = Schema::hasColumn('coa_items', 'total_cost');

        $mfgTotalRate = CoaItem::where('product_type', 'Moulding_FJ')
            ->whereNotIn('cost_type', ['Summary', 'Balance'])
            ->sum('standard_rate_per_ton');

        $mfgTotalCost = $hasTotalCost
            ? CoaItem::where('product_type', 'Moulding_FJ')
                ->whereNotIn('cost_type', ['Summary', 'Balance'])
                ->sum('total_cost')
            : 0.00;

        return view('filament.components.excel-coa-header', [
            'company'          => 'Pesama Timber Corporation Sdn. Bhd.',
            'title'            => 'Standard Costing Computation',
            'plant'            => 'Secondary Processing (Moulding & Finger Joint)',
            'capacityMoulding' => '150 Ton',
            'capacityFj'       => '75 Ton',
            'totalRate'        => number_format($mfgTotalRate, 2),
            'totalCost'        => number_format($mfgTotalCost, 2),
        ]);
    }

    protected function queryString(): array
    {
        return [];
    }
}