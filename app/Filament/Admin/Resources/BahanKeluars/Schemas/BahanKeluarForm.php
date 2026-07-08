<?php

namespace App\Filament\Admin\Resources\BahanKeluars\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BahanKeluarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('idbahan_masuk')
                    ->required()
                    ->numeric(),
                TextInput::make('bahan_id')
                    ->required()
                    ->numeric(),
                TextInput::make('sisa')
                    ->required()
                    ->numeric(),
                TextInput::make('qty_keluar')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('tanggal_keluar')
                    ->required(),
            ]);
    }
}
