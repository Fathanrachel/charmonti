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
                    ->label('Order')
                    ->options(Order::all()->pluck('id', 'id')->map(fn($id) => 'Order #' . $id))
                    ->required()
                    ->searchable(),

                Textarea::make('request_note')
                    ->label('Catatan Request')
                    ->nullable()
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('warna')
                    ->label('Warna')
                    ->nullable()
                    ->placeholder('contoh: Merah, Biru'),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending'   => 'Pending',
                        'disetujui' => 'Disetujui',
                        'ditolak'   => 'Ditolak',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }
}