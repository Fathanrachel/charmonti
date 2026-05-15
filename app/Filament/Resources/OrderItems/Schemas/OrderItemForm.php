<?php

namespace App\Filament\Resources\OrderItems\Schemas;

use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderItemForm
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

                Select::make('product_id')
                    ->label('Produk')
                    ->options(Product::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),

                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->required()
                    ->numeric()
                    ->minValue(1),

                TextInput::make('price')
                    ->label('Harga Satuan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
            ]);
    }
}