<?php

namespace App\Filament\Resources\Shippings\Schemas;

use App\Models\Order;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ShippingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->label('Order')
                    ->options(Order::all()->pluck('id', 'id')->map(fn($id) => 'Order #' . $id))
                    ->required()
                    ->searchable(),

                Select::make('payment_id')
                    ->label('Payment')
                    ->options(\App\Models\Payment::all()->pluck('id', 'id')->map(fn($id) => 'Payment #' . $id))
                    ->nullable()
                    ->searchable(),

                Select::make('expedition_id')
                    ->label('Ekspedisi / Kurir')
                    ->options(\App\Models\Expedition::all()->pluck('name_expedition', 'id'))
                    ->nullable()
                    ->searchable(),

                TextInput::make('shipping_cost')
                    ->label('Ongkos Kirim')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),

                TextInput::make('tracking_number')
                    ->label('Nomor Resi')
                    ->nullable()
                    ->placeholder('Isi setelah paket dikirim'),

                DateTimePicker::make('estimated_arrival')
                    ->label('Estimasi Tiba')
                    ->nullable(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending'  => 'Pending',
                        'dikirim'  => 'Dikirim',
                        'sampai'   => 'Sampai',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }
}