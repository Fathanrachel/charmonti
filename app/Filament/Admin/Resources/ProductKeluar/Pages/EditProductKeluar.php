<?php

namespace App\Filament\Admin\Resources\ProductKeluar\Pages;

use App\Filament\Admin\Resources\ProductKeluar\ProductKeluarResource;
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
