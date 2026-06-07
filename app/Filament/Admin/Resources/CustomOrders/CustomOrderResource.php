<?php

namespace App\Filament\Admin\Resources\CustomOrders;

use App\Filament\Admin\Resources\CustomOrders\Pages\CreateCustomOrder;
use App\Filament\Admin\Resources\CustomOrders\Pages\EditCustomOrder;
use App\Filament\Admin\Resources\CustomOrders\Pages\ListCustomOrders;
use App\Filament\Resources\CustomOrders\Schemas\CustomOrderForm;
use App\Filament\Resources\CustomOrders\Tables\CustomOrdersTable;
use App\Models\CustomOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomOrderResource extends Resource
{
    protected static ?string $model = CustomOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    public static function form(Schema $schema): Schema
    {
        return CustomOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomOrdersTable::configure($table);
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
            'index' => ListCustomOrders::route('/'),
            'create' => CreateCustomOrder::route('/create'),
            'edit' => EditCustomOrder::route('/{record}/edit'),
        ];
    }
}
