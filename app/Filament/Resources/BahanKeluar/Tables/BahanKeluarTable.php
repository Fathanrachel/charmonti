<?php

namespace App\Filament\Resources\BahanKeluar\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BahanKeluarTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bahan.nama_bahan')
                    ->label('Bahan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bahanMasuk.id')
                    ->label('Batch Masuk')
                    ->description(fn ($record) => $record->bahanMasuk?->tanggal_masuk ? 'Masuk: ' . $record->bahanMasuk->tanggal_masuk->translatedFormat('d M Y H:i') : '-')
                    ->sortable(),

                TextColumn::make('qty_keluar')
                    ->label('Jumlah Keluar')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('sisa')
                    ->label('Sisa Stok Batch')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('tanggal_keluar')
                    ->label('Tanggal Keluar')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
