<?php

namespace App\Filament\Admin\Resources\Bahan\Pages;

use App\Filament\Admin\Resources\Bahan\BahanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBahans extends ListRecords
{
    protected static string $resource = BahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
