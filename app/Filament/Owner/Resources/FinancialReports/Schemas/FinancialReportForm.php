<?php

namespace App\Filament\Owner\Resources\FinancialReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FinancialReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->required(),
                TextInput::make('income')
                    ->required()
                    ->numeric(),
                TextInput::make('expense')
                    ->required()
                    ->numeric(),
                TextInput::make('profit')
                    ->required()
                    ->numeric(),
            ]);
    }
}
