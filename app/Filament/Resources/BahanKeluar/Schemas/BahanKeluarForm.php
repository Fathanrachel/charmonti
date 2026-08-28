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
                        return BahanMasuk::with('bahan')->get()->mapWithKeys(function ($bm) {
                            $totalKeluar = BahanKeluar::where('idbahan_masuk', $bm->id)->sum('qty_keluar');
                            $sisa = max(0, $bm->qty_masuk - $totalKeluar);
                            $namaBahan = $bm->bahan?->nama_bahan ?? 'Bahan';
                            return [$bm->id => "{$namaBahan} (Masuk: {$bm->qty_masuk}, Sisa: {$sisa})"];
                        });
                    })
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $bm = BahanMasuk::with('bahan')->find($state);
                        if ($bm) {
                            $set('bahan_id', $bm->bahan_id);
                        } else {
                            $set('bahan_id', null);
                        }
                    }),

                Select::make('bahan_id')
                    ->label('Nama Bahan')
                    ->relationship('bahan', 'nama_bahan')
                    ->disabled()
                    ->dehydrated()
                    ->required(),

                TextInput::make('qty_keluar')
                    ->label('Jumlah Keluar')
                    ->required()
                    ->numeric()
                    ->minValue(1),

                DateTimePicker::make('tanggal_keluar')
                    ->label('Tanggal Keluar')
                    ->default(now())
                    ->required(),
            ]);
    }
}
