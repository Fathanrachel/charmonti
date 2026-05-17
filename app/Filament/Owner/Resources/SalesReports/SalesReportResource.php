<?php

namespace App\Filament\Owner\Resources\SalesReports;

use App\Filament\Owner\Resources\SalesReports\Pages\CreateSalesReport;
use App\Filament\Owner\Resources\SalesReports\Pages\EditSalesReport;
use App\Filament\Owner\Resources\SalesReports\Pages\ListSalesReports;
use App\Filament\Owner\Resources\SalesReports\Schemas\SalesReportForm;
use App\Filament\Owner\Resources\SalesReports\Tables\SalesReportsTable;
use App\Models\SalesReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SalesReportResource extends Resource
{
    protected static ?string $model = SalesReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'date';

    public static function form(Schema $schema): Schema
    {
        return SalesReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesReportsTable::configure($table);
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
            'index' => ListSalesReports::route('/'),
            'create' => CreateSalesReport::route('/create'),
            'edit' => EditSalesReport::route('/{record}/edit'),
        ];
    }
}
