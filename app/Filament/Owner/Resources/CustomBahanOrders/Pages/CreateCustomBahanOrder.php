<?php

namespace App\Filament\Owner\Resources\CustomBahanOrders\Pages;

use App\Filament\Owner\Resources\CustomBahanOrders\CustomBahanOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomBahanOrder extends CreateRecord
{
    protected static string $resource = CustomBahanOrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
