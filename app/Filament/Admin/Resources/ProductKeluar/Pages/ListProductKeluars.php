<?php

namespace App\Filament\Admin\Resources\ProductKeluar\Pages;

use App\Filament\Admin\Resources\ProductKeluar\ProductKeluarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductKeluars extends ListRecords
{
    protected static string $resource = ProductKeluarResource::class;

    public function getSubheading(): ?string
    {
        return 'Daftar riwayat pencatatan produk keluar.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
