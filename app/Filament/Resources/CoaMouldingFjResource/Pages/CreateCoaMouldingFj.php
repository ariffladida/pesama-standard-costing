<?php

namespace App\Filament\Resources\CoaMouldingFjResource\Pages;

use App\Filament\Resources\CoaMouldingFjResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCoaMouldingFj extends CreateRecord
{
    protected static string $resource = CoaMouldingFjResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['product_type'] = 'Moulding_FJ';
        return $data;
    }
}