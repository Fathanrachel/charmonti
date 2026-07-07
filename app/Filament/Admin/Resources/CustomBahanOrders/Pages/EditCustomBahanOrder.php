<?php

namespace App\Filament\Admin\Resources\CustomBahanOrders\Pages;

use App\Filament\Admin\Resources\CustomBahanOrders\CustomBahanOrderResource;
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
