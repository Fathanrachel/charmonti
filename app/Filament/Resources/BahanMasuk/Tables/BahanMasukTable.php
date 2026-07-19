<?php

namespace App\Filament\Resources\BahanMasuk\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BahanMasukTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_bahan')
                    ->label('Nama Bahan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('qty_masuk')
                    ->label('Jumlah Masuk')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('sisa_stok')
                    ->label('Sisa Stok Batch')
                    ->getStateUsing(function ($record) {
                        $alreadyOut = \App\Models\BahanKeluar::where('idbahan_masuk', $record->id)->sum('qty_keluar');
                        return $record->qty_masuk - $alreadyOut;
                    })
                    ->numeric()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    })
                    ->sortable(),

                TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->dateTime('d M Y')
                    ->sortable(),

                TextColumn::make('deskripsi')
                    ->label('Keterangan')
                    ->limit(50),
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
