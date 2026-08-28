<?php

namespace App\Filament\Admin\Resources\CityExpeditions\Schemas;

use App\Models\City;
use App\Models\Expedition;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CityExpeditionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('city_id')
                    ->label('Kota Tujuan')
                    ->options(function () {
                        return City::with('province')->get()->mapWithKeys(function ($city) {
                            $provName = $city->province?->province ?? '';
                            return [$city->id => "{$city->city} ({$provName})"];
                        });
                    })
                    ->required()
                    ->searchable(),

                Select::make('expedition_id')
                    ->label('Ekspedisi')
                    ->options(Expedition::all()->pluck('name_expedition', 'id'))
                    ->required()
                    ->searchable(),

                TextInput::make('shipping_cost')
                    ->label('Ongkos Kirim (Rp)')
                    ->numeric()
                    ->required()
                    ->minValue(0),

                TextInput::make('estimated_days')
                    ->label('Estimasi Pengiriman (Hari)')
                    ->numeric()
                    ->required()
                    ->minValue(1),
            ]);
    }
}
