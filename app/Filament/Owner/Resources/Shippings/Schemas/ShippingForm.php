<?php

namespace App\Filament\Owner\Resources\Shippings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ShippingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_id')
                    ->required()
                    ->numeric(),
                TextInput::make('courier')
                    ->required(),
                TextInput::make('shipping_cost')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                TextInput::make('tracking_number'),
                DateTimePicker::make('estimated_arrival'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
            ]);
    }
}
