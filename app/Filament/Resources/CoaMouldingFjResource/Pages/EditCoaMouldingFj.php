<?php

namespace App\Filament\Resources\CoaMouldingFjResource\Pages;

use App\Filament\Resources\CoaMouldingFjResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoaMouldingFj extends EditRecord
{
    protected static string $resource = CoaMouldingFjResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
