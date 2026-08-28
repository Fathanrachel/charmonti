<?php

namespace App\Filament\Resources\BahanKeluar\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BahanKeluarTable
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

                TextColumn::make('bahan.nama_bahan')
                    ->label('Bahan / Charm')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('qty_keluar')
                    ->label('Jumlah Keluar')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('sisa_stok_running')
                    ->label('Sisa Stok')
                    ->getStateUsing(function ($record) {
                        $totalMasuk = \App\Models\BahanMasuk::where('bahan_id', $record->bahan_id)->sum('qty_masuk');
                        // For grouped rows, we need the max id in the group (order_id + bahan_id)
                        $lastIdInGroup = \App\Models\BahanKeluar::where('bahan_id', $record->bahan_id)
                            ->where('order_id', $record->order_id)
                            ->max('id');
                        $totalKeluarUpToThis = \App\Models\BahanKeluar::where('bahan_id', $record->bahan_id)
                            ->where('id', '<=', ($lastIdInGroup ?? $record->id))
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
