<?php

namespace App\Filament\Admin\Resources\Orders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

use Filament\Forms\Components\Select;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('order_date')
                    ->required(),
                Select::make('status')
                    ->options([
                        'pending'  => 'Pending (Menunggu Pembayaran)',
                        'diproses' => 'Diproses (Sedang Disiapkan)',
                        'dikirim'  => 'Dikirim (Dalam Pengiriman)',
                        'selesai'  => 'Selesai',
                        'batal'    => 'Dibatalkan',
                    ])
                    ->required()
                    ->default('pending'),
                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                TextInput::make('payment_method'),
                Textarea::make('shipping_address')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
