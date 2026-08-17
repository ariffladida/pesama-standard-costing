<?php

namespace App\Filament\Resources\MouldingCostingResource\Pages;

use App\Filament\Resources\MouldingCostingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMouldingCostings extends ListRecords
{
    protected static string $resource = MouldingCostingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
