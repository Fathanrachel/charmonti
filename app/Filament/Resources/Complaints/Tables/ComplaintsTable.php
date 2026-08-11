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
                    ->state(fn ($record) => $record->complaintCategory?->name ?? $record->category ?? '-')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('message')
                    ->label('Detail Aduan')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('status')
                    ->label('Status Tindak Lanjut')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'open', 'pending'   => 'danger',
                        'diproses'          => 'info',
                        'selesai', 'closed' => 'success',
                        default             => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'open', 'pending'   => 'Komplain Baru',
                        'diproses'          => 'Sedang Ditangani',
                        'selesai', 'closed' => 'Selesai Ditangani',
                        default             => ucfirst($state ?? '-'),
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Pengaduan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
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