<?php

namespace App\Filament\Resources\ProductMasuk\Pages;

use App\Filament\Resources\ProductMasuk\ProductMasukResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductMasuks extends ListRecords
{
    protected static string $resource = ProductMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
