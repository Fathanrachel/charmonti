<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->label('Foto Produk')
                    ->image()
                    ->directory('products')
                    ->imagePreviewHeight('200')
                    ->columnSpanFull(),

                TextInput::make('product_name')
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->nullable()
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('price')
                    ->label('Harga')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),

                Select::make('category')
                    ->label('Kategori Produk')
                    ->options([
                        'gelang_jadi' => 'Gelang Jadi',
                        'cincin'      => 'Cincin',
                    ])
                    ->required()
                    ->native(false)
                    ->default('gelang_jadi'),
            ]);
    }
}