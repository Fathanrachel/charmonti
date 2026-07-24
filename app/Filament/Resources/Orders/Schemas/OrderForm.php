<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Profile;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('profile_id')
                    ->label('Customer')
                    ->options(
                        Profile::where('role', 'customer')
                            ->get()
                            ->pluck('name', 'id')
                    )
                    ->required()
                    ->searchable(),

                DateTimePicker::make('order_date')
                    ->label('Tanggal Order')
                    ->required()
                    ->default(now()),

                Select::make('status')
                    ->label('Status Pesanan')
                    ->options([
                        'pending'   => 'Menunggu Pembayaran',
                        'diproses'  => 'Sedang Diproses (Stok Dipotong)',
                        'dikirim'   => 'Dalam Pengiriman (Sudah Di Kurir)',
                        'selesai'   => 'Pesanan Selesai (Diterima Customer)',
                        'batal'     => 'Dibatalkan (Pengembalian Stok)',
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
            ]);
    }
}