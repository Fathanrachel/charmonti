<?php

namespace App\Filament\Admin\Resources\Variasis\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VariasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_variasi')
                    ->label('Nama Variasi Bahan')
                    ->placeholder('Misal: Tali Gelang / Charm Manik Huruf')
                    ->required()
                    ->maxLength(255),

                Textarea::make('deskripsi')
                    ->label('Deskripsi Variasi')
                    ->placeholder('Penjelasan mengenai kelompok variasi bahan ini...')
                    ->rows(3),
            ]);
    }
}
