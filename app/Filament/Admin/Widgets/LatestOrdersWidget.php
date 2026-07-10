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
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('payment.payment_status')
                    ->label('Status Bayar')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'paid' => 'Lunas',
                        'pending' => 'Pending',
                        'failed' => 'Gagal',
                        default => $state,
                    }),

                TextColumn::make('shipping.status')
                    ->label('Status Pengiriman')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sampai' => 'success',
                        'dikirim' => 'info',
                        'pending' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'sampai' => 'Diterima',
                        'dikirim' => 'Dikirim',
                        'pending' => 'Diproses',
                        default => 'Belum Bayar',
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
