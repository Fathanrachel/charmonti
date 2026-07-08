<?php

namespace App\Filament\Owner\Resources\Bahan\Pages;

use App\Filament\Owner\Resources\Bahan\BahanResource;
use Filament\Resources\Pages\EditRecord;

class EditBahan extends EditRecord
{
    protected static string $resource = BahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
