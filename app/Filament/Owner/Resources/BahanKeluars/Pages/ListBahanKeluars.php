<?php

namespace App\Filament\Owner\Resources\BahanKeluars\Pages;

use App\Filament\Owner\Resources\BahanKeluars\BahanKeluarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBahanKeluars extends ListRecords
{
    protected static string $resource = BahanKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
