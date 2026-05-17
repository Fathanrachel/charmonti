<?php

namespace App\Filament\Owner\Resources\CustomOrders\Pages;

use App\Filament\Owner\Resources\CustomOrders\CustomOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomOrder extends CreateRecord
{
    protected static string $resource = CustomOrderResource::class;
}
