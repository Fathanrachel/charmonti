<?php

namespace App\Filament\Resources\Bahan\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BahanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->label('Foto Bahan')
                    ->image()
                    ->directory('materials')
                    ->imagePreviewHeight('200')
                    ->columnSpanFull(),

                TextInput::make('nama_bahan')
                    ->label('Nama Bahan')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->nullable()
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('price')
                    ->label('Harga Satuan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
            ]);
    }
}
