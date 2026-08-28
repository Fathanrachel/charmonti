<?php

namespace App\Filament\Admin\Resources\BahanMasuks\Pages;

use App\Filament\Admin\Resources\BahanMasuks\BahanMasukResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBahanMasuk extends EditRecord
{
    protected static string $resource = BahanMasukResource::class;

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
