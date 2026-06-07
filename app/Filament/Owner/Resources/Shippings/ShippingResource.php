<?php

namespace App\Filament\Owner\Resources\Shippings;

use App\Filament\Owner\Resources\Shippings\Pages\CreateShipping;
use App\Filament\Owner\Resources\Shippings\Pages\EditShipping;
use App\Filament\Owner\Resources\Shippings\Pages\ListShippings;
use App\Filament\Resources\Shippings\Schemas\ShippingForm;
use App\Filament\Resources\Shippings\Tables\ShippingsTable;
use App\Models\Shipping;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ShippingResource extends Resource
{
    protected static ?string $model = Shipping::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static ?string $recordTitleAttribute = 'courier';

    public static function form(Schema $schema): Schema
    {
        return ShippingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShippingsTable::configure($table);
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
            'index' => ListShippings::route('/'),
            'create' => CreateShipping::route('/create'),
            'edit' => EditShipping::route('/{record}/edit'),
        ];
    }
}
