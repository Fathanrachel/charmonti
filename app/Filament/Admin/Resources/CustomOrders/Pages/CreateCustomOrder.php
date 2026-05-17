<?php

namespace App\Filament\Admin\Resources\CustomOrders\Pages;

use App\Filament\Admin\Resources\CustomOrders\CustomOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomOrder extends CreateRecord
{
    protected static string $resource = CustomOrderResource::class;
}
