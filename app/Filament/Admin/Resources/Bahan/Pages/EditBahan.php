<?php

namespace App\Filament\Admin\Resources\Bahan\Pages;

use App\Filament\Admin\Resources\Bahan\BahanResource;
use Filament\Resources\Pages\EditRecord;

class EditBahan extends EditRecord
{
    protected static string $resource = BahanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
