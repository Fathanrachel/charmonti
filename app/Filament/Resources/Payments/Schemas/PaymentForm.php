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
                    ->label('Order')
                    ->options(Order::all()->pluck('id', 'id')->map(fn($id) => 'Order #' . $id))
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
                        'pending' => 'Pending',
                        'paid'    => 'Paid',
                        'failed'  => 'Failed',
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