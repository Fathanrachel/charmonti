<?php

namespace App\Filament\Admin\Resources\CustomOrders\Pages;

use App\Filament\Admin\Resources\CustomOrders\CustomOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomOrders extends ListRecords
{
    protected static string $resource = CustomOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
