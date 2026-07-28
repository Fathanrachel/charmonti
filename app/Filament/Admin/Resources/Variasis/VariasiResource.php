<?php

namespace App\Filament\Admin\Resources\Variasis;

use App\Filament\Admin\Resources\Variasis\Pages\CreateVariasi;
use App\Filament\Admin\Resources\Variasis\Pages\EditVariasi;
use App\Filament\Admin\Resources\Variasis\Pages\ListVariasis;
use App\Filament\Admin\Resources\Variasis\Schemas\VariasiForm;
use App\Filament\Admin\Resources\Variasis\Tables\VariasisTable;
use App\Models\Variasi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class VariasiResource extends Resource
{
    protected static ?string $model = Variasi::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationLabel = 'Kelola Variasi';

    protected static ?string $modelLabel = 'Variasi Bahan';

    protected static ?string $pluralModelLabel = 'Kelola Variasi';

    protected static ?string $recordTitleAttribute = 'nama_variasi';

    public static function form(Schema $schema): Schema
    {
        return VariasiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VariasisTable::configure($table);
    }

    public static function canViewAny(): bool
    {
        $role = auth()->user()?->profile?->role;
        return in_array($role, ['admin', 'stok', 'store']);
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
            'index' => ListVariasis::route('/'),
            'create' => CreateVariasi::route('/create'),
            'edit' => EditVariasi::route('/{record}/edit'),
        ];
    }
}
