<?php

namespace App\Filament\Owner\Resources\FinancialReports;

use App\Filament\Owner\Resources\FinancialReports\Pages\CreateFinancialReport;
use App\Filament\Owner\Resources\FinancialReports\Pages\EditFinancialReport;
use App\Filament\Owner\Resources\FinancialReports\Pages\ListFinancialReports;
use App\Filament\Owner\Resources\FinancialReports\Schemas\FinancialReportForm;
use App\Filament\Owner\Resources\FinancialReports\Tables\FinancialReportsTable;
use App\Models\FinancialReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FinancialReportResource extends Resource
{
    protected static ?string $model = FinancialReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'date';

    public static function form(Schema $schema): Schema
    {
        return FinancialReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FinancialReportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFinancialReports::route('/'),
            'create' => CreateFinancialReport::route('/create'),
            'edit' => EditFinancialReport::route('/{record}/edit'),
        ];
    }
}
