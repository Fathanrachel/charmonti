<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Order #')
                    ->sortable(),

                TextColumn::make('profile.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('order_date')
                    ->label('Tanggal Order')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status Pesanan')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending'  => 'warning',
                        'diproses' => 'info',
                        'dikirim'  => 'primary',
                        'selesai'  => 'success',
                        'batal'    => 'danger',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'pending'  => 'Menunggu Pembayaran',
                        'diproses' => 'Sedang Diproses',
                        'dikirim'  => 'Dalam Pengiriman',
                        'selesai'  => 'Pesanan Selesai',
                        'batal'    => 'Dibatalkan',
                        default    => ucfirst($state ?? '-'),
                    }),

                TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Pembayaran')
                    ->searchable(),
            ])
            ->defaultSort('order_date', 'desc')
            ->filters([])
            ->recordActions([
                EditAction::make(),

                Action::make('ubahStatus')
                    ->label('Ubah Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->form([
                        Select::make('status')
                            ->label('Status Baru')
                            ->options([
                                'pending'  => 'Menunggu Pembayaran',
                                'diproses' => 'Sedang Diproses (Stok Dipotong)',
                                'dikirim'  => 'Dalam Pengiriman',
                                'selesai'  => 'Pesanan Selesai',
                                'batal'    => 'Dibatalkan (Pengembalian Stok)',
                            ])
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update(['status' => $data['status']]);
                    })
                    ->successNotificationTitle('Status order berhasil diubah!'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}