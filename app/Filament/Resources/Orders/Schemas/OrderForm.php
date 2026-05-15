<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Customer')
                    ->options(User::where('role', 'customer')->pluck('name', 'id'))
                    ->required()
                    ->searchable(),

                DateTimePicker::make('order_date')
                    ->label('Tanggal Order')
                    ->required()
                    ->default(now()),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending'   => 'Pending',
                        'diproses'  => 'Diproses',
                        'selesai'   => 'Selesai',
                        'batal'     => 'Batal',
                    ])
                    ->default('pending')
                    ->required(),

                TextInput::make('total_price')
                    ->label('Total Harga')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),

                Select::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'transfer'  => 'Transfer Bank',
                        'QRIS'      => 'QRIS',
                        'midtrans'  => 'Midtrans',
                    ])
                    ->nullable(),

                Textarea::make('shipping_address')
                    ->label('Alamat Pengiriman')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}