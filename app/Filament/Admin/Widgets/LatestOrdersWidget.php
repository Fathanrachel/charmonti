<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;

class LatestOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Transaksi Pesanan Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()->latest()->limit(5)
            )
            ->columns([
                TextColumn::make('id')
                    ->label('ID Order')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => "#{$state}"),

                TextColumn::make('profile.name')
                    ->label('Nama Pelanggan')
                    ->placeholder('Tanpa Nama')
                    ->sortable(),

                TextColumn::make('order_date')
                    ->label('Tanggal Order')
                    ->dateTime('d M Y')
                    ->sortable(),

                TextColumn::make('payment_status_display')
                    ->label('Status Bayar')
                    ->badge()
                    ->state(function (Order $record): string {
                        $status = $record->payment?->payment_status ?? 'pending';
                        return match ($status) {
                            'paid' => 'paid',
                            'failed', 'cancel', 'expire', 'deny' => 'failed',
                            default => 'pending',
                        };
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'failed' => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'paid' => 'Lunas',
                        'failed' => 'Gagal',
                        default => 'Belum Bayar',
                    }),

                TextColumn::make('shipping.status')
                    ->label('Status Pengiriman')
                    ->badge()
                    ->color(fn (?string $state, Order $record): string => match ($state) {
                        'sampai' => 'success',
                        'dikirim' => 'info',
                        'pending' => $record->payment?->payment_status === 'paid' ? 'warning' : 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state, Order $record): string => match ($state) {
                        'sampai' => 'Diterima',
                        'dikirim' => 'Dikirim',
                        'pending' => $record->payment?->payment_status === 'paid' ? 'Diproses' : 'Menunggu Pembayaran',
                        default => 'Menunggu Pembayaran',
                    })
                    ->placeholder('Menunggu Pembayaran'),

                TextColumn::make('total_price')
                    ->label('Total Belanja')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
            ])
            ->actions([
                \Filament\Actions\Action::make('Detail')
                    ->url(fn (Order $record): string => "/admin/orders/{$record->id}/edit")
                    ->icon('heroicon-m-eye')
                    ->color('rose'),
            ]);
    }
}
