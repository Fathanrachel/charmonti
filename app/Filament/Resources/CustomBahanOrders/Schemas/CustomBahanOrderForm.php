<?php

namespace App\Filament\Resources\CustomBahanOrders\Schemas;

use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CustomBahanOrderForm
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

                Textarea::make('request_note')
                    ->label('Catatan Request')
                    ->nullable()
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('warna')
                    ->label('Warna Tali')
                    ->nullable()
                    ->placeholder('contoh: Silver, Gold'),

                Select::make('status')
                    ->label('Status Perakitan')
                    ->options([
                        'pending'   => 'Menunggu Perakitan',
                        'diproses'  => 'Sedang Dirangkai',
                        'selesai'   => 'Selesai Dirangkai',
                        'batal'     => 'Dibatalkan',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }
}