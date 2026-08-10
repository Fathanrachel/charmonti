<?php

namespace App\Filament\Resources\CustomBahanOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomBahanOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.id')
                    ->label('Order #')
                    ->sortable(),

                TextColumn::make('warna')
                    ->label('Warna Tali')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status Perakitan')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending'               => 'warning',
                        'diproses', 'disetujui' => 'info',
                        'selesai'               => 'success',
                        'batal', 'ditolak'      => 'danger',
                        default                 => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'pending'               => 'Menunggu Perakitan',
                        'diproses'              => 'Sedang Dirangkai',
                        'disetujui'             => 'Disetujui',
                        'selesai'               => 'Selesai Dirangkai',
                        'batal', 'ditolak'      => 'Dibatalkan',
                        default                 => ucfirst($state ?? '-'),
                    }),

                TextColumn::make('created_at')
                    ->label('Tanggal Request')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
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