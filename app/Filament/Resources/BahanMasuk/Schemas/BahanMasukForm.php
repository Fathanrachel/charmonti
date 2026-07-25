<?php

namespace App\Filament\Resources\BahanMasuk\Schemas;

use App\Models\Bahan;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BahanMasukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('bahan_id')
                    ->label('Nama Bahan')
                    ->options(Bahan::all()->pluck('nama_bahan', 'id'))
                    ->required()
                    ->searchable(),

                TextInput::make('qty_masuk')
                    ->label('Jumlah Masuk')
                    ->required()
                    ->numeric()
                    ->minValue(1),

                DateTimePicker::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->default(now())
                    ->required(),

                Textarea::make('deskripsi')
                    ->label('Keterangan / Catatan')
                    ->columnSpanFull(),
            ]);
    }
}
