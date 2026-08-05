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
                TextColumn::make('order.profile.name')
                    ->label('Pesanan / Pelanggan')
                    ->formatStateUsing(fn ($state, $record) => "Pesanan #{$record->order_id} - " . ($state ?? 'Pelanggan'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('payment_type')
                    ->label('Metode')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'transfer'  => 'info',
                        'QRIS'      => 'warning',
                        'midtrans'  => 'success',
                        default     => 'gray',
                    }),

                TextColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid'    => 'success',
                        'failed'  => 'danger',
                        'expired' => 'gray',
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'pending' => 'Menunggu Pembayaran',
                        'paid'    => 'Pembayaran Lunas',
                        'failed'  => 'Gagal / Dibatalkan',
                        'expired' => 'Kadaluwarsa',
                        default   => ucfirst($state ?? '-'),
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
                \Filament\Actions\Action::make('cekStatus')
                    ->label('Cek Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->action(function ($record) {
                        try {
                            \Midtrans\Config::$serverKey = config('midtrans.server_key');
                            \Midtrans\Config::$isProduction = config('midtrans.is_production');
                            \Midtrans\Config::$isSanitized = true;
                            \Midtrans\Config::$is3ds = true;

                            $orderId = $record->transaction_id;
                            
                            // If stored ID is a UUID, guess the correct ORDER-ID format using created_at timestamp range
                            if (strpos($orderId, 'ORDER-') !== 0) {
                                $baseTimestamp = $record->created_at->timestamp;
                                $found = false;
                                
                                for ($diff = -5; $diff <= 5; $diff++) {
                                    $guessedId = 'ORDER-' . $record->order_id . '-' . ($baseTimestamp + $diff);
                                    try {
                                        $status = \Midtrans\Transaction::status($guessedId);
                                        if (isset($status->transaction_status)) {
                                            $orderId = $guessedId;
                                            $found = true;
                                            break;
                                        }
                                    } catch (\Exception $e) {
                                        // Ignore exception and try next timestamp deviation
                                    }
                                }

                                if (!$found) {
                                    throw new \Exception("Gagal mendeteksi Order ID Midtrans untuk pesanan ini. Silakan sync manual via Midtrans Dashboard.");
                                }
                            }
                            
                            $status = \Midtrans\Transaction::status($orderId);
                            $transactionStatus = $status->transaction_status ?? 'pending';

                            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                                $record->update([
                                    'payment_status' => 'paid',
                                    'payment_date' => now(),
                                    'payment_type' => in_array($status->payment_type ?? '', ['transfer', 'QRIS']) ? $status->payment_type : 'midtrans',
                                    'transaction_id' => $orderId
                                ]);

                                $record->order->update(['status' => 'diproses']);

                                \Filament\Notifications\Notification::make()
                                    ->title('Lunas!')
                                    ->body('Pembayaran berhasil diverifikasi oleh Midtrans.')
                                    ->success()
                                    ->send();
                            } else {
                                \Filament\Notifications\Notification::make()
                                    ->title('Belum Lunas')
                                    ->body('Status Midtrans: ' . strtoupper($transactionStatus))
                                    ->warning()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            $message = $e->getMessage();
                            if (strpos($message, '404') !== false || strpos($message, "doesn't exist") !== false) {
                                $message = "Transaksi ini belum pernah dibuka atau diinisialisasi di halaman pembayaran Midtrans oleh pelanggan.";
                            }
                            \Filament\Notifications\Notification::make()
                                ->title('Gagal Sinkronisasi')
                                ->body($message)
                                ->danger()
                                ->send();
                        }
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}