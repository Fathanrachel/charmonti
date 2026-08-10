<?php

namespace App\Filament\Resources\Shippings\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ShippingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.profile.name')
                    ->label('Nama Penerima')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('order.id')
                    ->label('No. Pesanan')
                    ->formatStateUsing(fn ($state) => "#{$state}")
                    ->sortable(),

                TextColumn::make('expedition.name_expedition')
                    ->label('Kurir / Ekspedisi')
                    ->searchable(),

                TextColumn::make('shipping_cost')
                    ->label('Ongkos Kirim')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('tracking_number')
                    ->label('Nomor Resi')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('estimated_arrival')
                    ->label('Estimasi Tiba')
                    ->dateTime('d M Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status Pengiriman')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending'           => 'warning',
                        'dikirim'           => 'info',
                        'sampai', 'selesai' => 'success',
                        'batal'             => 'danger',
                        default             => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'pending'           => 'Menunggu Pengiriman',
                        'dikirim'           => 'Dalam Pengiriman',
                        'sampai', 'selesai' => 'Sudah Terkirim / Sampai',
                        'batal'             => 'Batal Dikirim',
                        default             => ucfirst($state ?? '-'),
                    }),
            ])
            ->filters([])
            ->recordActions([
                Action::make('ubahStatusPengiriman')
                    ->label('Ubah Status Pengiriman')
                    ->icon('heroicon-o-truck')
                    ->color('warning')
                    ->form([
                        Select::make('status')
                            ->label('Status Pengiriman Baru')
                            ->native(false)
                            ->live()
                            ->options([
                                'pending' => 'Menunggu Pengiriman (Sedang Disiapkan)',
                                'dikirim' => 'Dalam Pengiriman (Di Kurir)',
                                'sampai'  => 'Sudah Terkirim / Sampai',
                                'batal'   => 'Batal Dikirim',
                            ])
                            ->default(fn ($record) => $record?->status ?? 'pending')
                            ->required(),

                        TextInput::make('tracking_number')
                            ->label('Nomor Resi *')
                            ->required(fn ($get) => in_array($get('status'), ['dikirim', 'sampai']))
                            ->placeholder('Masukkan nomor resi pengiriman (Contoh: JNE123456789)')
                            ->default(fn ($record) => $record?->tracking_number),

                        Select::make('staff_id')
                            ->label('Staff Penanggung Jawab (Pegawai)')
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
                            ->default(function ($record) {
                                if ($record?->order?->staff_id && $record->order->staff?->profile?->role !== 'customer') {
                                    return $record->order->staff_id;
                                }
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                if ($user && in_array($user->profile?->role, ['admin', 'kasir', 'stok', 'store', 'owner'])) {
                                    return $user->id;
                                }
                                return null;
                            })
                            ->nullable(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->status = $data['status'];
                        if (isset($data['tracking_number'])) {
                            $record->tracking_number = $data['tracking_number'];
                        }
                        $record->save();

                        // Auto update linked Order status & staff_id
                        if ($record->order) {
                            $order = $record->order;
                            $orderStatus = match ($data['status']) {
                                'dikirim'           => 'dikirim',
                                'sampai', 'selesai' => 'selesai',
                                'pending'           => 'diproses',
                                'batal'             => 'batal',
                                default             => $order->status,
                            };
                            $order->status = $orderStatus;
                            if (isset($data['staff_id']) && $data['staff_id']) {
                                $order->staff_id = $data['staff_id'];
                            } elseif (Auth::check()) {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                if ($user && in_array($user->profile?->role, ['admin', 'kasir', 'stok', 'store', 'owner'])) {
                                    $order->staff_id = $user->id;
                                }
                            }
                            $order->saveQuietly();
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