<?php

namespace App\Filament\Resources\CoaItemResource\Pages;

use App\Filament\Resources\CoaItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoaItem extends EditRecord
{
    protected static string $resource = CoaItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
