<?php

namespace App\Filament\Resources\MouldingCostingResource\Pages;

use App\Filament\Resources\MouldingCostingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMouldingCosting extends EditRecord
{
    protected static string $resource = MouldingCostingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
