<?php

namespace App\Filament\Resources\ProductKeluar\Pages;

use App\Filament\Resources\ProductKeluar\ProductKeluarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductKeluars extends ListRecords
{
    protected static string $resource = ProductKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
