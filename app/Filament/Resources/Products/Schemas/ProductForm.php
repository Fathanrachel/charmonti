<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
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

                TextInput::make('name')
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
                        'gelang_custom' => 'Gelang Custom (Charm)',
                        'gelang_jadi'   => 'Gelang Jadi',
                        'cincin'        => 'Cincin',
                    ])
                    ->required()
                    ->default('gelang_jadi'),

                Toggle::make('is_custom')
                    ->label('Produk Custom?')
                    ->default(false),
            ]);
    }
}