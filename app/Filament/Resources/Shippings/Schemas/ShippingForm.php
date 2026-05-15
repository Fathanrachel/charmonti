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

                TextInput::make('courier')
                    ->label('Kurir')
                    ->required()
                    ->placeholder('contoh: JNE, J&T, SiCepat'),

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