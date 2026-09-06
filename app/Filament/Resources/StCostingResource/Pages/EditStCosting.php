<?php

namespace App\Filament\Resources\StCostingResource\Pages;

use App\Filament\Resources\StCostingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStCosting extends EditRecord
{
    protected static string $resource = StCostingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
