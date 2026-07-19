<?php

namespace App\Filament\Admin\Resources\BahanKeluars\Pages;

use App\Filament\Admin\Resources\BahanKeluars\BahanKeluarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBahanKeluars extends ListRecords
{
    protected static string $resource = BahanKeluarResource::class;

    public function getSubheading(): ?string
    {
        return 'Stok otomatis dipotong dari batch terlama terlebih dahulu.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
