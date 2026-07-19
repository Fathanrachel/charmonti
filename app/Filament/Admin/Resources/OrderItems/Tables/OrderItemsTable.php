<?php

namespace App\Filament\Admin\Resources\OrderItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.id')
                    ->label('ID Order')
                    ->getStateUsing(fn ($record) => '#' . $record->order_id)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('order.profile.name')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Guest'),
                TextColumn::make('product.product_name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Gelang Custom'),
                TextColumn::make('price')
                    ->label('Harga Satuan')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                TextColumn::make('qty')
                    ->label('Jumlah (Qty)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->label('Total Harga')
                    ->money('IDR', locale: 'id')
                    ->getStateUsing(fn ($record) => $record->qty * $record->price)
                    ->sortable(),
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
