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
                    ->label('Nama Produk')
                    ->options(Product::all()->pluck('product_name', 'id'))
                    ->required()
                    ->searchable(),

                TextInput::make('qty_masuk')
                    ->label('Jumlah Masuk')
                    ->numeric()
                    ->required()
                    ->minValue(1),

                DateTimePicker::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->default(now())
                    ->required(),

                Textarea::make('deskripsi')
                    ->label('Keterangan / Catatan')
                    ->nullable()
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
