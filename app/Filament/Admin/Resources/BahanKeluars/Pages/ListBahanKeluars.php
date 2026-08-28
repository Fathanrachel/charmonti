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
        return 'Daftar riwayat pencatatan bahan keluar.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
