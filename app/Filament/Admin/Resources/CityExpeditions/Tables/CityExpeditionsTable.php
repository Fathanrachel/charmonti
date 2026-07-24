<?php

namespace App\Filament\Admin\Resources\CityExpeditions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CityExpeditionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('city.city')
                    ->label('Kota Tujuan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('city.province.province')
                    ->label('Provinsi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('expedition.name_expedition')
                    ->label('Ekspedisi')
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
            ])
            ->filters([
                SelectFilter::make('expedition_id')
                    ->label('Filter Ekspedisi')
                    ->relationship('expedition', 'name_expedition'),
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
