<?php

namespace App\Filament\Resources\Bahan\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
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

                Select::make('variasi_id')
                    ->relationship('variasi', 'nama_variasi')
                    ->label('Kelompok Variasi Bahan')
                    ->placeholder('Pilih Variasi Bahan...')
                    ->searchable()
                    ->nullable()
                    ->native(false),

                TextInput::make('kegunaan')
                    ->label('Kegunaan / Fungsi Bahan')
                    ->placeholder('Contoh: Tali Utama Gelang, Hiasan Liontin Tengah')
                    ->nullable(),

                TextInput::make('price')
                    ->label('Harga Satuan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),

                TextInput::make('min_stock')
                    ->label('Batas Minimum Stok (Warning)')
                    ->helperText('Batas stok terendah sebelum muncul peringatan warning')
                    ->required()
                    ->numeric()
                    ->default(1),
            ]);
    }
}
