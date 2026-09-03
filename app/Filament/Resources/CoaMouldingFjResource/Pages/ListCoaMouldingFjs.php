<?php

namespace App\Filament\Resources\CoaMouldingFjResource\Pages;

use App\Filament\Resources\CoaMouldingFjResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCoaMouldingFjs extends ListRecords
{
    protected static string $resource = CoaMouldingFjResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function queryString(): array
    {
        return [];
    }
}