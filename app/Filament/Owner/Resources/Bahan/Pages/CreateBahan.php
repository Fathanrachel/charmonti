<?php

namespace App\Filament\Owner\Resources\Bahan\Pages;

use App\Filament\Owner\Resources\Bahan\BahanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBahan extends CreateRecord
{
    protected static string $resource = BahanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
