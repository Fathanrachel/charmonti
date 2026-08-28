<?php

namespace App\Filament\Owner\Resources\BahanMasuks\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BahanMasukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bahan_id')
                    ->required()
                    ->numeric(),
                TextInput::make('nama_bahan')
                    ->required(),
                TextInput::make('qty_masuk')
                    ->required()
                    ->numeric(),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                DateTimePicker::make('tanggal_masuk')
                    ->required(),
            ]);
    }
}
