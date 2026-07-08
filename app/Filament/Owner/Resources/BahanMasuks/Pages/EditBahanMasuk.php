<?php

namespace App\Filament\Owner\Resources\BahanMasuks\Pages;

use App\Filament\Owner\Resources\BahanMasuks\BahanMasukResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBahanMasuk extends EditRecord
{
    protected static string $resource = BahanMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
