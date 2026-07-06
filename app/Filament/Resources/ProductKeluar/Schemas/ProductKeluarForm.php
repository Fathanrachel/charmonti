<?php

namespace App\Filament\Resources\ProductKeluar\Schemas;

use App\Models\Product;
use App\Models\ProductMasuk;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductKeluarForm
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

                Select::make('idproduct_masuk')
                    ->label('Batch Masuk')
                    ->options(ProductMasuk::all()->pluck('nama_product', 'id'))
                    ->required()
                    ->searchable(),

                TextInput::make('qty_keluar')
                    ->label('Jumlah Keluar')
                    ->numeric()
                    ->required(),

                TextInput::make('sisa')
                    ->label('Sisa Batch')
                    ->numeric()
                    ->required(),

                DateTimePicker::make('tanggal_keluar')
                    ->label('Tanggal Keluar')
                    ->required(),
            ]);
    }
}
