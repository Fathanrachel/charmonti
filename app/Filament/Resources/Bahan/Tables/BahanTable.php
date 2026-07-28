<?php

namespace App\Filament\Resources\Bahan\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BahanTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(asset('images/no-image.png')),

                TextColumn::make('nama_bahan')
                    ->label('Nama Bahan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('variasi.nama_variasi')
                    ->label('Kelompok Variasi')
                    ->default('-')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kegunaan')
                    ->label('Kegunaan / Fungsi')
                    ->default('-')
                    ->limit(40),

                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('dynamic_stock')
                    ->label('Total Stok')
                    ->getStateUsing(fn ($record) => $record->dynamic_stock)
                    ->numeric()
                    ->badge()
                    ->color(fn ($state, $record): string => match(true) {
                        $state <= 0                          => 'danger',
                        $state <= ($record->min_stock ?? 1)  => 'warning',
                        default                              => 'success',
                    })
                    ->formatStateUsing(fn ($state, $record) => match(true) {
                        $state <= 0                          => 'Habis (0)',
                        $state <= ($record->min_stock ?? 1)  => "⚠️ Stok Menipis ({$state} pcs)",
                        default                              => "{$state} pcs",
                    })
                    ->sortable(),

                TextColumn::make('min_stock')
                    ->label('Min. Stok')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
