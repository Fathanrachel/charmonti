<?php

namespace App\Filament\Admin\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_id')
                    ->required()
                    ->numeric(),
                TextInput::make('payment_type')
                    ->required(),
                TextInput::make('payment_status')
                    ->required()
                    ->default('pending'),
                TextInput::make('transaction_id'),
                DateTimePicker::make('payment_date'),
            ]);
    }
}
