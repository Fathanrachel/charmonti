<?php

namespace App\Filament\Owner\Resources\ProductKeluar\Pages;

use App\Filament\Owner\Resources\ProductKeluar\ProductKeluarResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductKeluar extends EditRecord
{
    protected static string $resource = ProductKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
