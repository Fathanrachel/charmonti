<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Models\Order;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->label('Pesanan (Pelanggan)')
                    ->options(fn () => Order::with('profile')->get()->mapWithKeys(function ($order) {
                        $name = $order->profile?->name ?? 'Pelanggan';
                        return [$order->id => "Pesanan #{$order->id} - {$name}"];
                    }))
                    ->required()
                    ->searchable(),

                Select::make('payment_type')
                    ->label('Metode Pembayaran')
                    ->options([
                        'transfer'  => 'Transfer Bank',
                        'QRIS'      => 'QRIS',
                        'midtrans'  => 'Midtrans',
                    ])
                    ->required(),

                Select::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'paid'    => 'Pembayaran Lunas',
                        'failed'  => 'Gagal / Dibatalkan',
                        'expired' => 'Kadaluwarsa',
                    ])
                    ->default('pending')
                    ->required(),

                TextInput::make('transaction_id')
                    ->label('ID Transaksi')
                    ->nullable()
                    ->placeholder('Isi jika pakai Midtrans'),

                DateTimePicker::make('payment_date')
                    ->label('Tanggal Pembayaran')
                    ->nullable(),
            ]);
    }
}