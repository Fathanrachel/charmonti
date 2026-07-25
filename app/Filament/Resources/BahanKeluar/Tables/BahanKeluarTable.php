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

                TextColumn::make('qty_keluar')
                    ->label('Jumlah Keluar')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('bahan.dynamic_stock')
                    ->label('Sisa Stok')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('tanggal_keluar')
                    ->label('Tanggal Keluar')
                    ->dateTime('d M Y')
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
