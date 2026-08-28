<?php

namespace App\Filament\Admin\Resources\BahanKeluars\Pages;

use App\Filament\Admin\Resources\BahanKeluars\BahanKeluarResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBahanKeluar extends CreateRecord
{
    protected static string $resource = BahanKeluarResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
