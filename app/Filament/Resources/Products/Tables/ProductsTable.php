<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->where('product_name', '!=', 'Gelang Custom'))
            ->columns([
                ImageColumn::make('image')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(asset('images/no-image.png')),

                TextColumn::make('product_name')
                    ->label('Nama Produk')
                    ->searchable(),

                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('dynamic_stock')
                    ->label('Stok (Sisa)')
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

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'gelang_jadi' => 'success',
                        'cincin'      => 'danger',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'gelang_jadi' => 'Gelang Jadi',
                        'cincin'      => 'Cincin',
                        default       => ucwords(str_replace('_', ' ', $state)),
                    }),

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