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
                    ->label('Nama Produk')
                    ->options(Product::all()->pluck('product_name', 'id'))
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('idproduct_masuk', null)),

                Select::make('idproduct_masuk')
                    ->label('Stok Masuk Terkait')
                    ->options(function (callable $get) {
                        $productId = $get('product_id');
                        $query = ProductMasuk::with('product');
                        if ($productId) {
                            $query->where('product_id', $productId);
                        }
                        return $query->get()->mapWithKeys(function ($pm) {
                            $totalKeluar = \App\Models\ProductKeluar::where('idproduct_masuk', $pm->id)->sum('qty_keluar');
                            $sisa = max(0, $pm->qty_masuk - $totalKeluar);
                            $namaProduk = $pm->product?->product_name ?? 'Produk';
                            return [$pm->id => "{$namaProduk} (Masuk: {$pm->qty_masuk}, Sisa: {$sisa})"];
                        });
                    })
                    ->required()
                    ->searchable(),

                TextInput::make('qty_keluar')
                    ->label('Jumlah Keluar')
                    ->numeric()
                    ->required()
                    ->minValue(1),

                DateTimePicker::make('tanggal_keluar')
                    ->label('Tanggal Keluar')
                    ->default(now())
                    ->required(),
            ]);
    }
}