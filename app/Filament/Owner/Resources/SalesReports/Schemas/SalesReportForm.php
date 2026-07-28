<?php

namespace App\Filament\Owner\Resources\SalesReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SalesReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->relationship('order', 'id')
                    ->label('Pesanan # (Order ID)')
                    ->nullable()
                    ->searchable(),
                DatePicker::make('date')
                    ->label('Tanggal Laporan')
                    ->required(),
                TextInput::make('total_orders')
                    ->label('Jumlah Pesanan')
                    ->required()
                    ->numeric(),
                TextInput::make('total_revenue')
                    ->label('Total Pendapatan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
            ]);
    }
}
