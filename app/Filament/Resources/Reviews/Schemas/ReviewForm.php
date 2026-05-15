<?php

namespace App\Filament\Resources\Reviews\Schemas;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReviewForm
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

                Select::make('product_id')
                    ->label('Produk')
                    ->options(Product::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),

                Select::make('rating')
                    ->label('Rating')
                    ->options([
                        1 => '⭐ 1 - Sangat Buruk',
                        2 => '⭐⭐ 2 - Buruk',
                        3 => '⭐⭐⭐ 3 - Cukup',
                        4 => '⭐⭐⭐⭐ 4 - Bagus',
                        5 => '⭐⭐⭐⭐⭐ 5 - Sangat Bagus',
                    ])
                    ->required(),

                Textarea::make('comment')
                    ->label('Komentar')
                    ->nullable()
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}