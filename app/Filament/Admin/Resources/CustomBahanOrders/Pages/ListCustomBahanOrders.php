<?php

namespace App\Filament\Admin\Resources\CustomBahanOrders\Pages;

use App\Filament\Admin\Resources\CustomBahanOrders\CustomBahanOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomBahanOrders extends ListRecords
{
    protected static string $resource = CustomBahanOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
