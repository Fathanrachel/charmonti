<?php

namespace App\Filament\Admin\Resources\BahanMasuks\Pages;

use App\Filament\Admin\Resources\BahanMasuks\BahanMasukResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBahanMasuks extends ListRecords
{
    protected static string $resource = BahanMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
