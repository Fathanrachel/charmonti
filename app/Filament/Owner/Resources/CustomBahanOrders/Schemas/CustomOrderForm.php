<?php

namespace App\Filament\Owner\Resources\CustomOrders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CustomOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_id')
                    ->required()
                    ->numeric(),
                Textarea::make('request_note')
                    ->columnSpanFull(),
                TextInput::make('ukuran'),
                TextInput::make('warna'),
                TextInput::make('tambahan_aksesoris'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                Textarea::make('remarks_assessment')
                    ->columnSpanFull(),
            ]);
    }
}
