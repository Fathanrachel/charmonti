<?php

namespace App\Filament\Admin\Resources\Variasis\Pages;

use App\Filament\Admin\Resources\Variasis\VariasiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVariasi extends EditRecord
{
    protected static string $resource = VariasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Edit Variasi Bahan: ' . $this->record->nama_variasi;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
