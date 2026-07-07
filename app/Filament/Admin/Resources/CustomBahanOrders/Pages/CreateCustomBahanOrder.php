<?php

namespace App\Filament\Admin\Resources\CustomBahanOrders\Pages;

use App\Filament\Admin\Resources\CustomBahanOrders\CustomBahanOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomBahanOrder extends CreateRecord
{
    protected static string $resource = CustomBahanOrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
