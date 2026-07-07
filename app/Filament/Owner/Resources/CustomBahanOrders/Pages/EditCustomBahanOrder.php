<?php

namespace App\Filament\Owner\Resources\CustomBahanOrders\Pages;

use App\Filament\Owner\Resources\CustomBahanOrders\CustomBahanOrderResource;
use Filament\Resources\Pages\EditRecord;

class EditCustomBahanOrder extends EditRecord
{
    protected static string $resource = CustomBahanOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
