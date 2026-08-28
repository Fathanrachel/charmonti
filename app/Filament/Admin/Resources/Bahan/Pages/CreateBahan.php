<?php

namespace App\Filament\Admin\Resources\Bahan\Pages;

use App\Filament\Admin\Resources\Bahan\BahanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBahan extends CreateRecord
{
    protected static string $resource = BahanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
