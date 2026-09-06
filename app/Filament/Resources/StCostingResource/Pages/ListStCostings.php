<?php

namespace App\Filament\Resources\StCostingResource\Pages;

use App\Filament\Resources\StCostingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStCostings extends ListRecords
{
    protected static string $resource = StCostingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
