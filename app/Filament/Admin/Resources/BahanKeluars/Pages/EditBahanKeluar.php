<?php

namespace App\Filament\Admin\Resources\BahanKeluars\Pages;

use App\Filament\Admin\Resources\BahanKeluars\BahanKeluarResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBahanKeluar extends EditRecord
{
    protected static string $resource = BahanKeluarResource::class;

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
