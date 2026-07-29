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

                TextColumn::make('staff.profile.name')
                    ->label('Staff Penanggung Jawab')
                    ->state(function ($record) {
                        if (!$record->staff) return 'Belum Ada';
                        $name = $record->staff->profile?->name ?? $record->staff->name ?? $record->staff->email;
                        $role = match($record->staff->profile?->role) {
                            'admin' => 'Admin',
                            'kasir' => 'Kasir',
                            'stok', 'store' => 'Stok',
                            'owner' => 'Owner',
                            default => ucfirst($record->staff->profile?->role ?? 'Staff'),
                        };
                        return "{$name} - {$role}";
                    })
                    ->default('Belum Ada')
                    ->searchable()
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
                    ->hidden(fn ($record): bool => in_array($record?->status, ['selesai', 'batal']))
                    ->form([
                        Select::make('status')
                            ->label('Status Baru')
                            ->native(false)
                            ->options(function ($record): array {
                                $current = $record?->status;

                                if ($current === 'pending') {
                                    return [
                                        'diproses' => 'Sedang Diproses (Stok Dipotong)',
                                        'dikirim'  => 'Dalam Pengiriman',
                                        'selesai'  => 'Pesanan Selesai',
                                        'batal'    => 'Dibatalkan (Pengembalian Stok)',
                                    ];
                                }

                                if ($current === 'diproses') {
                                    return [
                                        'dikirim'  => 'Dalam Pengiriman',
                                        'selesai'  => 'Pesanan Selesai',
                                        'batal'    => 'Dibatalkan (Pengembalian Stok)',
                                    ];
                                }

                                if ($current === 'dikirim') {
                                    return [
                                        'selesai'  => 'Pesanan Selesai',
                                        'batal'    => 'Dibatalkan (Pengembalian Stok)',
                                    ];
                                }

                                return [];
                            })
                            ->required(),

                        Select::make('staff_id')
                            ->label('Tugaskan Staff Penanggung Jawab')
                            ->native(false)
                            ->searchable()
                            ->options(function () {
                                return \App\Models\User::whereHas('profile', function ($q) {
                                    $q->whereIn('role', ['admin', 'kasir', 'stok', 'store', 'owner']);
                                })->get()->mapWithKeys(function ($user) {
                                    $name = $user->profile?->name ?? $user->name ?? $user->email;
                                    $role = match($user->profile?->role) {
                                        'admin' => 'Admin',
                                        'kasir' => 'Kasir',
                                        'stok', 'store' => 'Stok',
                                        'owner' => 'Owner',
                                        default => ucfirst($user->profile?->role ?? 'Staff'),
                                    };
                                    return [$user->id => "{$name} - {$role}"];
                                });
                            })
                            ->default(fn ($record) => $record?->staff_id ?? auth()->id())
                            ->nullable(),
                    ])
                    ->action(function ($record, array $data) {
                        $updateData = ['status' => $data['status']];
                        if (isset($data['staff_id']) && $data['staff_id']) {
                            $updateData['staff_id'] = $data['staff_id'];
                        } elseif (!$record->staff_id && auth()->check()) {
                            $updateData['staff_id'] = auth()->id();
                        }

                        $record->update($updateData);

                        if ($data['status'] === 'dikirim' && $record->shipping) {
                            $record->shipping->update(['status' => 'dikirim']);
                        } elseif ($data['status'] === 'selesai' && $record->shipping) {
                            $record->shipping->update(['status' => 'sampai']);
                        }
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