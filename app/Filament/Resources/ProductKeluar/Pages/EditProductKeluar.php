<?php

namespace App\Filament\Resources\ProductKeluar\Pages;

use App\Filament\Resources\ProductKeluar\ProductKeluarResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductKeluar extends EditRecord
{
    protected static string $resource = ProductKeluarResource::class;

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
