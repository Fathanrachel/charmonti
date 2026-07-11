<?php

namespace App\Filament\Admin\Resources\Expeditions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExpeditionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name_expedition')
                    ->label('Nama Ekspedisi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('shipping_cost')
                    ->label('Ongkos Kirim')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                TextColumn::make('estimated_days')
                    ->label('Estimasi Pengiriman')
                    ->numeric()
                    ->suffix(' Hari')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Ditambahkan')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y H:i')
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
