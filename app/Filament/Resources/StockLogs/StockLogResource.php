<?php

namespace App\Filament\Resources\StockLogs;

use App\Filament\Resources\StockLogs\Pages\CreateStockLog;
use App\Filament\Resources\StockLogs\Pages\EditStockLog;
use App\Filament\Resources\StockLogs\Pages\ListStockLogs;
use App\Filament\Resources\StockLogs\Schemas\StockLogForm;
use App\Filament\Resources\StockLogs\Tables\StockLogsTable;
use App\Models\StockLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockLogResource extends Resource
{
    protected static ?string $model = StockLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return StockLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockLogsTable::configure($table);
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
            'index' => ListStockLogs::route('/'),
            'create' => CreateStockLog::route('/create'),
            'edit' => EditStockLog::route('/{record}/edit'),
        ];
    }
}
