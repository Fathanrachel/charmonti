<?php

namespace App\Filament\Resources\ProductKeluar\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class ProductKeluarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.product_name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('qty_keluar')
                    ->label('Jumlah Keluar')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('product.dynamic_stock')
                    ->label('Sisa Stok')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('tanggal_keluar')
                    ->label('Tanggal Keluar')
                    ->dateTime('d M Y')
                    ->sortable(),
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
