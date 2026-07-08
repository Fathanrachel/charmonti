<?php

namespace App\Filament\Resources\ProductMasuk\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class ProductMasuksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.product_name')
                    ->label('Produk')
                    ->searchable(),

                TextColumn::make('nama_product')
                    ->label('Nama Batch')
                    ->searchable(),

                TextColumn::make('qty_masuk')
                    ->label('Jumlah Masuk')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('sisa_stok')
                    ->label('Sisa Stok Batch')
                    ->getStateUsing(function ($record) {
                        $alreadyOut = $record->productKeluar()->sum('qty_keluar');
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
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                BadgeColumn::make('product.category')
                    ->label('Kategori')
                    ->colors([
                        'warning' => 'charm',
                        'info' => 'strap',
                        'success' => 'gelang_jadi',
                        'danger' => 'cincin',
                    ])
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'charm' => 'Charm',
                        'strap' => 'Strap',
                        'gelang_jadi' => 'Gelang Jadi',
                        'cincin' => 'Cincin',
                        default => $state ?? '-',
                    }),
            ])
            ->filters([
                Filter::make('product.category')
                    ->label('Kategori')
                    ->query(fn ($query) => $query->whereHas('product', fn ($query) => $query->where('category', request('category')))),
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
