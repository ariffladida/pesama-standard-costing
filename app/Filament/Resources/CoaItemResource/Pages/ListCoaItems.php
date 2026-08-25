<?php

namespace App\Filament\Resources\CoaItemResource\Pages;

use App\Filament\Resources\CoaItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCoaItems extends ListRecords
{
    protected static string $resource = CoaItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
