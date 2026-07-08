<?php

namespace App\Filament\Admin\Resources\ProductMasuk\Pages;

use App\Filament\Admin\Resources\ProductMasuk\ProductMasukResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductMasuk extends EditRecord
{
    protected static string $resource = ProductMasukResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
