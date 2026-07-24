<?php

namespace App\Filament\Admin\Resources\Expeditions;

use App\Filament\Admin\Resources\Expeditions\Pages\CreateExpedition;
use App\Filament\Admin\Resources\Expeditions\Pages\EditExpedition;
use App\Filament\Admin\Resources\Expeditions\Pages\ListExpeditions;
use App\Filament\Admin\Resources\Expeditions\Schemas\ExpeditionForm;
use App\Filament\Admin\Resources\Expeditions\Tables\ExpeditionsTable;
use App\Models\Expedition;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ExpeditionResource extends Resource
{
    protected static ?string $model = Expedition::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    protected static UnitEnum|string|null $navigationGroup = 'Ekspedisi';

    protected static ?string $navigationLabel = 'Master Ekspedisi';

    protected static ?string $modelLabel = 'Master Ekspedisi';

    protected static ?string $pluralModelLabel = 'Master Ekspedisi';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name_expedition';

    public static function form(Schema $schema): Schema
    {
        return ExpeditionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpeditionsTable::configure($table);
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
            'index' => ListExpeditions::route('/'),
            'create' => CreateExpedition::route('/create'),
            'edit' => EditExpedition::route('/{record}/edit'),
        ];
    }
}
