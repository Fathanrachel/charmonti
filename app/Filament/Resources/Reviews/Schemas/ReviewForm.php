<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->relationship('order', 'id')
                    ->label('Pesanan #')
                    ->disabled()
                    ->required(),

                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Pelanggan')
                    ->disabled()
                    ->required(),

                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->label('Produk')
                    ->disabled()
                    ->required(),

                TextInput::make('rating')
                    ->numeric()
                    ->label('Rating (Bintang)')
                    ->disabled()
                    ->required(),

                Textarea::make('comment')
                    ->label('Komentar Ulasan')
                    ->disabled()
                    ->columnSpanFull(),
            ]);
    }
}