<?php

namespace App\Filament\Owner\Resources\BahanKeluars\Pages;

use App\Filament\Owner\Resources\BahanKeluars\BahanKeluarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBahanKeluars extends ListRecords
{
    protected static string $resource = BahanKeluarResource::class;

    public function getSubheading(): ?string
    {
        return 'Alur keluar bahan dipotong secara otomatis menggunakan metode FIFO (First In First Out) — stok dari batch paling lama dikeluarkan terlebih dahulu.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
