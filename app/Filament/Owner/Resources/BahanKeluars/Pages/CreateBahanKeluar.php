<?php

namespace App\Filament\Owner\Resources\BahanKeluars\Pages;

use App\Filament\Owner\Resources\BahanKeluars\BahanKeluarResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBahanKeluar extends CreateRecord
{
    protected static string $resource = BahanKeluarResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
