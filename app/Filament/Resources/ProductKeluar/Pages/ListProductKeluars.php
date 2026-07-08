<?php

namespace App\Filament\Resources\ProductKeluar\Pages;

use App\Filament\Resources\ProductKeluar\ProductKeluarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductKeluars extends ListRecords
{
    protected static string $resource = ProductKeluarResource::class;

    public function getSubheading(): ?string
    {
        return 'Alur keluar barang dipotong secara otomatis menggunakan metode FIFO (First In First Out) — stok dari batch paling lama dikeluarkan terlebih dahulu.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
