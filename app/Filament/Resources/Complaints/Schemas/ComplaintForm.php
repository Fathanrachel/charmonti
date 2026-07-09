<?php

namespace App\Filament\Resources\Complaints\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ComplaintForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->relationship('order', 'id')
                    ->label('Pesanan #')
                    ->disabled()
                    ->required(),

                Select::make('user_id')
                    ->relationship('user', 'email')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->profile?->name ?? $record->email)
                    ->label('Pelanggan')
                    ->disabled()
                    ->required(),

                TextInput::make('category')
                    ->label('Kategori Masalah')
                    ->disabled()
                    ->required(),

                Select::make('status')
                    ->label('Status Tindak Lanjut')
                    ->options([
                        'open' => 'Baru / Menunggu',
                        'diproses' => 'Sedang Diproses',
                        'selesai' => 'Selesai / Teratasi',
                    ])
                    ->required()
                    ->native(false),

                Textarea::make('message')
                    ->label('Isi Pengaduan Keluhan')
                    ->disabled()
                    ->columnSpanFull()
                    ->required(),

                Textarea::make('reply_message')
                    ->label('Balasan / Tanggapan Toko')
                    ->placeholder('Tulis balasan atau solusi yang ditawarkan toko di sini...')
                    ->columnSpanFull(),
            ]);
    }
}