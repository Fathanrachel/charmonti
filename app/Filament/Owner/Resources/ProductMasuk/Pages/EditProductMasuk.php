<?php

namespace App\Filament\Owner\Resources\ProductMasuk\Pages;

use App\Filament\Owner\Resources\ProductMasuk\ProductMasukResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductMasuk extends EditRecord
{
    protected static string $resource = ProductMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
