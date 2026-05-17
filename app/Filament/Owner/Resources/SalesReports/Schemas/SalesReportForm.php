<?php

namespace App\Filament\Owner\Resources\SalesReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SalesReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->required(),
                TextInput::make('total_orders')
                    ->required()
                    ->numeric(),
                TextInput::make('total_revenue')
                    ->required()
                    ->numeric(),
            ]);
    }
}
