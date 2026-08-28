<?php

namespace App\Filament\Admin\Resources\Expeditions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ExpeditionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name_expedition')
                    ->label('Nama Ekspedisi / Kurir')
                    ->required(),
                TextInput::make('shipping_cost')
                    ->label('Ongkos Kirim')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('Rp'),
                TextInput::make('estimated_days')
                    ->label('Estimasi Hari Pengiriman')
                    ->required()
                    ->numeric()
                    ->default(3)
                    ->suffix('Hari'),
            ]);
    }
}
