<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.id')
                    ->label('Order #')
                    ->sortable(),

                TextColumn::make('payment_type')
                    ->label('Metode')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'transfer'  => 'info',
                        'QRIS'      => 'warning',
                        'midtrans'  => 'success',
                    }),

                TextColumn::make('payment_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid'    => 'success',
                        'failed'  => 'danger',
                    }),

                TextColumn::make('transaction_id')
                    ->label('ID Transaksi')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('payment_date')
                    ->label('Tanggal Bayar')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
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