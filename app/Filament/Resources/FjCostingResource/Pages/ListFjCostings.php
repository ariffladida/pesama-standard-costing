<?php

namespace App\Filament\Resources\FjCostingResource\Pages;

use App\Filament\Resources\FjCostingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFjCostings extends ListRecords
{
    protected static string $resource = FjCostingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('+ Kira Kos FJ Baharu'),
        ];
    }

    protected function queryString(): array
    {
        return [];
    }
}