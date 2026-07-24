<?php

namespace App\Filament\Admin\Resources\CityExpeditions;

use App\Filament\Admin\Resources\CityExpeditions\Pages\CreateCityExpedition;
use App\Filament\Admin\Resources\CityExpeditions\Pages\EditCityExpedition;
use App\Filament\Admin\Resources\CityExpeditions\Pages\ListCityExpeditions;
use App\Filament\Admin\Resources\CityExpeditions\Schemas\CityExpeditionForm;
use App\Filament\Admin\Resources\CityExpeditions\Tables\CityExpeditionsTable;
use App\Models\CityExpedition;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CityExpeditionResource extends Resource
{
    protected static ?string $model = CityExpedition::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static UnitEnum|string|null $navigationGroup = 'Ekspedisi';

    protected static ?string $navigationLabel = 'Tarif Ekspedisi Kota';

    protected static ?string $modelLabel = 'Tarif Ekspedisi Kota';

    protected static ?string $pluralModelLabel = 'Tarif Ekspedisi Kota';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return CityExpeditionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CityExpeditionsTable::configure($table);
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
            'index' => ListCityExpeditions::route('/'),
            'create' => CreateCityExpedition::route('/create'),
            'edit' => EditCityExpedition::route('/{record}/edit'),
        ];
    }
}
