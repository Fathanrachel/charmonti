<?php

namespace App\Filament\Resources\CustomOrders\Schemas;

use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CustomOrderForm
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

                TextInput::make('ukuran')
                    ->label('Ukuran')
                    ->nullable()
                    ->placeholder('contoh: S, M, L atau 16cm'),

                TextInput::make('warna')
                    ->label('Warna')
                    ->nullable()
                    ->placeholder('contoh: Merah, Biru'),

                TextInput::make('tambahan_aksesoris')
                    ->label('Tambahan Aksesoris')
                    ->nullable()
                    ->placeholder('contoh: Liontin Bintang'),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending'   => 'Pending',
                        'disetujui' => 'Disetujui',
                        'ditolak'   => 'Ditolak',
                    ])
                    ->default('pending')
                    ->required(),

                Textarea::make('remarks_assessment')
                    ->label('Catatan Penilaian')
                    ->nullable()
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}