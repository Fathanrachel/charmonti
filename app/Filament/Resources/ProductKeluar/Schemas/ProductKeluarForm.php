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
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('idproduct_masuk', null)),

                Select::make('idproduct_masuk')
                    ->label('Batch Masuk')
                    ->options(function (callable $get) {
                        $productId = $get('product_id');
                        if (!$productId) {
                            return ProductMasuk::all()->pluck('nama_product', 'id');
                        }
                        return ProductMasuk::where('product_id', $productId)->pluck('nama_product', 'id');
                    })
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (!$state) {
                            $set('sisa', 0);
                            return;
                        }
                        $productMasuk = ProductMasuk::find($state);
                        if ($productMasuk) {
                            // Hitung sisa stok batch: qty_masuk - total qty_keluar pada batch ini
                            $totalKeluar = \App\Models\ProductKeluar::where('idproduct_masuk', $state)->sum('qty_keluar');
                            $sisaStok = $productMasuk->qty_masuk - $totalKeluar;
                            $set('sisa', max(0, $sisaStok));
                        }
                    }),

                TextInput::make('qty_keluar')
                    ->label('Jumlah Keluar')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $batchId = $get('idproduct_masuk');
                        if ($batchId) {
                            $productMasuk = ProductMasuk::find($batchId);
                            if ($productMasuk) {
                                $totalKeluarLain = \App\Models\ProductKeluar::where('idproduct_masuk', $batchId)
                                    ->where('id', '!=', $get('id')) // Abaikan record edit saat ini
                                    ->sum('qty_keluar');
                                $sisaStokSebelumnya = $productMasuk->qty_masuk - $totalKeluarLain;
                                $set('sisa', max(0, $sisaStokSebelumnya - ($state ?: 0)));
                            }
                        }
                    }),

                TextInput::make('sisa')
                    ->label('Sisa Batch')
                    ->numeric()
                    ->required()
                    ->disabled()
                    ->dehydrated(),

                DateTimePicker::make('tanggal_keluar')
                    ->label('Tanggal Keluar')
                    ->default(now())
                    ->required(),
            ]);
    }
}
