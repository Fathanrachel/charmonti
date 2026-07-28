<?php

namespace App\Filament\Admin\Resources\Bahan;

use App\Filament\Admin\Resources\Bahan\Pages\CreateBahan;
use App\Filament\Admin\Resources\Bahan\Pages\EditBahan;
use App\Filament\Admin\Resources\Bahan\Pages\ListBahans;
use App\Filament\Resources\Bahan\Schemas\BahanForm;
use App\Filament\Resources\Bahan\Tables\BahanTable;
use App\Models\Bahan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BahanResource extends Resource
{
    protected static ?string $model = Bahan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquare3Stack3d;

    protected static ?string $navigationLabel = 'Kelola Bahan';

    protected static ?string $modelLabel = 'Bahan';

    protected static ?string $pluralModelLabel = 'Bahan';

    protected static ?string $recordTitleAttribute = 'nama_bahan';

    public static function form(Schema $schema): Schema
    {
        return BahanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BahanTable::configure($table);
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
            'index'  => ListBahans::route('/'),
            'create' => CreateBahan::route('/create'),
            'edit'   => EditBahan::route('/{record}/edit'),
        ];
    }
}
