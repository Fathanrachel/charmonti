<?php

namespace App\Filament\Resources\BahanKeluar\Schemas;

use App\Models\BahanMasuk;
use App\Models\BahanKeluar;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BahanKeluarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idbahan_masuk')
                    ->label('Batch Bahan Masuk')
                    ->options(function () {
                        return BahanMasuk::all()->mapWithKeys(function ($bm) {
                            // Calculate current remaining in this batch
                            $totalKeluar = BahanKeluar::where('idbahan_masuk', $bm->id)->sum('qty_keluar');
                            $sisa = $bm->qty_masuk - $totalKeluar;
                            return [$bm->id => "{$bm->nama_bahan} (Masuk: {$bm->qty_masuk}, Sisa Batch: {$sisa})"];
                        });
                    })
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $bm = BahanMasuk::find($state);
                        if ($bm) {
                            $set('bahan_id', $bm->bahan_id);
                            $totalKeluar = BahanKeluar::where('idbahan_masuk', $bm->id)->sum('qty_keluar');
                            $sisa = $bm->qty_masuk - $totalKeluar;
                            $set('sisa', $sisa);
                        } else {
                            $set('bahan_id', null);
                            $set('sisa', 0);
                        }
                    }),

                Select::make('bahan_id')
                    ->label('Bahan Master')
                    ->relationship('bahan', 'nama_bahan')
                    ->disabled()
                    ->dehydrated()
                    ->required(),

                TextInput::make('sisa')
                    ->label('Sisa Stok Batch Saat Ini')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('qty_keluar')
                    ->label('Jumlah Keluar')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->reactive()
                    ->afterStateUpdated(function ($state, $get, callable $set) {
                        $sisa = intval($get('sisa'));
                        if ($state > $sisa) {
                            // Validasi input melebihi sisa stok batch
                            $set('qty_keluar', $sisa);
                        }
                    }),

                DateTimePicker::make('tanggal_keluar')
                    ->label('Tanggal Keluar')
                    ->default(now())
                    ->required(),
            ]);
    }
}
