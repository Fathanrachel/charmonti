<?php

namespace App\Filament\Admin\Resources\ProductMasuk;

use App\Filament\Admin\Resources\ProductMasuk\Pages\CreateProductMasuk;
use App\Filament\Admin\Resources\ProductMasuk\Pages\EditProductMasuk;
use App\Filament\Admin\Resources\ProductMasuk\Pages\ListProductMasuks;
use App\Filament\Resources\ProductMasuk\Schemas\ProductMasukForm;
use App\Filament\Resources\ProductMasuk\Tables\ProductMasuksTable;
use App\Models\ProductMasuk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductMasukResource extends Resource
{
    protected static ?string $model = ProductMasuk::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowDownTray;

    protected static ?string $recordTitleAttribute = 'nama_product';

    public static function form(Schema $schema): Schema
    {
        return ProductMasukForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductMasuksTable::configure($table);
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
            'index' => ListProductMasuks::route('/'),
            'create' => CreateProductMasuk::route('/create'),
            'edit' => EditProductMasuk::route('/{record}/edit'),
        ];
    }
}
