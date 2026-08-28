<?php

namespace App\Filament\Admin\Resources\Variasis\Pages;

use App\Filament\Admin\Resources\Variasis\VariasiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVariasis extends ListRecords
{
    protected static string $resource = VariasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Variasi Bahan'),
        ];
    }
}
