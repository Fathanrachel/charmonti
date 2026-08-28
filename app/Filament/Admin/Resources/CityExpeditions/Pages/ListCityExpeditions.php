<?php

namespace App\Filament\Admin\Resources\CityExpeditions\Pages;

use App\Filament\Admin\Resources\CityExpeditions\CityExpeditionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCityExpeditions extends ListRecords
{
    protected static string $resource = CityExpeditionResource::class;

    public function getSubheading(): ?string
    {
        return 'Kelola tarif ongkos kirim dan estimasi pengiriman per kota tujuan.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
