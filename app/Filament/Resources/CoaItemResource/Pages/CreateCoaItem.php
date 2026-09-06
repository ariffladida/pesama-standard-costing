<?php

namespace App\Filament\Resources\CoaItemResource\Pages;

use App\Filament\Resources\CoaItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCoaItem extends CreateRecord
{
    protected static string $resource = CoaItemResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['product_type'] = 'Sawmill';
        return $data;
    }
}