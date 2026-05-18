<?php

namespace App\Filament\Resources\Shippings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShippingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.id')
                    ->label('Order #')
                    ->sortable(),

                TextColumn::make('courier')
                    ->label('Kurir')
                    ->searchable(),

                TextColumn::make('shipping_cost')
                    ->label('Ongkos Kirim')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('tracking_number')
                    ->label('Nomor Resi')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('estimated_arrival')
                    ->label('Estimasi Tiba')
                    ->dateTime('d M Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'  => 'warning',
                        'dikirim'  => 'info',
                        'sampai'   => 'success',
                    }),
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