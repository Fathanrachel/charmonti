<?php

namespace App\Filament\Owner\Resources\BahanMasuks\Pages;

use App\Filament\Owner\Resources\BahanMasuks\BahanMasukResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBahanMasuk extends CreateRecord
{
    protected static string $resource = BahanMasukResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
