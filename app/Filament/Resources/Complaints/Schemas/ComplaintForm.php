<?php

namespace App\Filament\Resources\Complaints\Schemas;

use App\Models\Order;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ComplaintForm
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

                Select::make('user_id')
                    ->label('Customer')
                    ->options(User::where('role', 'customer')->pluck('name', 'id'))
                    ->required()
                    ->searchable(),

                Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'produk'    => 'Masalah Produk',
                        'pengiriman'=> 'Masalah Pengiriman',
                        'pembayaran'=> 'Masalah Pembayaran',
                        'lainnya'   => 'Lainnya',
                    ])
                    ->required(),

                Textarea::make('message')
                    ->label('Pesan Komplain')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'open'     => 'Open',
                        'diproses' => 'Diproses',
                        'selesai'  => 'Selesai',
                    ])
                    ->default('open')
                    ->required(),
            ]);
    }
}