<?php

namespace App\Filament\Resources\ProductMasuk\Schemas;

use App\Models\Product;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductMasukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Produk')
                    ->options(Product::all()->pluck('product_name', 'id'))
                    ->required()
                    ->searchable(),

                TextInput::make('nama_product')
                    ->label('Nama Batch')
                    ->required()
                    ->maxLength(255),

                TextInput::make('qty_masuk')
                    ->label('Jumlah Masuk')
                    ->numeric()
                    ->required(),

                Textarea::make('deskripsi')
                    ->label('Keterangan')
                    ->nullable()
                    ->rows(3)
                    ->columnSpanFull(),

                DateTimePicker::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->required(),
            ]);
    }
}
