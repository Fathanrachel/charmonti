<?php

namespace App\Filament\Resources\Complaints\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ComplaintsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.id')
                    ->label('Pesanan #')
                    ->sortable(),

                TextColumn::make('user.profile.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Kategori Masalah')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('message')
                    ->label('Detail Aduan')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open'     => 'danger',
                        'diproses' => 'warning',
                        'selesai'  => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'open'     => 'Baru / Menunggu',
                        'diproses' => 'Diproses Toko',
                        'selesai'  => 'Selesai / Teratasi',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Pengaduan')
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