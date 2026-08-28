<?php

namespace App\Filament\Admin\Resources\Variasis\Pages;

use App\Filament\Admin\Resources\Variasis\VariasiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVariasi extends CreateRecord
{
    protected static string $resource = VariasiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
