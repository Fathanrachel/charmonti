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
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => 
                        $set('nama_bahan', Bahan::find($state)?->nama_bahan ?? '')
                    ),
                
                TextInput::make('nama_bahan')
                    ->label('Nama Katalog Bahan')
                    ->required()
                    ->maxLength(255),

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
