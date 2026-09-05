<?php

namespace App\Filament\Resources\FjCostingResource\Pages;

use App\Filament\Resources\FjCostingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFjCosting extends EditRecord
{
    protected static string $resource = FjCostingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
