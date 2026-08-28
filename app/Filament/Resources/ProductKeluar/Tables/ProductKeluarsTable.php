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
                TextColumn::make('order.id')
                    ->label('Order #')
                    ->prefix('#')
                    ->sortable()
                    ->default('-'),

                TextColumn::make('order.profile.name')
                    ->label('Pelanggan')
                    ->default(fn ($record) => $record->deskripsi ?? 'Pengurangan Manual')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('product.product_name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('qty_keluar')
                    ->label('Jumlah Keluar')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('sisa_stok_running')
                    ->label('Sisa Stok')
                    ->getStateUsing(function ($record) {
                        $totalMasuk = \App\Models\ProductMasuk::where('product_id', $record->product_id)->sum('qty_masuk');
                        $totalKeluarUpToThis = \App\Models\ProductKeluar::where('product_id', $record->product_id)
                            ->where('id', '<=', $record->id)
                            ->sum('qty_keluar');
                        return max(0, $totalMasuk - $totalKeluarUpToThis);
                    })
                    ->numeric()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    }),

                TextColumn::make('tanggal_keluar')
                    ->label('Tanggal Keluar')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('tanggal_keluar', 'desc')
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
