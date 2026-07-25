<?php

namespace App\Filament\Owner\Resources\BahanKeluars\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BahanKeluarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('idbahan_masuk')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('bahan_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('bahan.dynamic_stock')
                    ->label('Sisa Stok')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('qty_keluar')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tanggal_keluar')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
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
