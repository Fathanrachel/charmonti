<?php

namespace App\Filament\Owner\Resources\Shippings\Pages;

use App\Filament\Owner\Resources\Shippings\ShippingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListShippings extends ListRecords
{
    protected static string $resource = ShippingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
