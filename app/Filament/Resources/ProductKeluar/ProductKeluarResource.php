<?php

namespace App\Filament\Resources\ProductKeluar;

use App\Filament\Resources\ProductKeluar\Pages\CreateProductKeluar;
use App\Filament\Resources\ProductKeluar\Pages\EditProductKeluar;
use App\Filament\Resources\ProductKeluar\Pages\ListProductKeluars;
use App\Filament\Resources\ProductKeluar\Schemas\ProductKeluarForm;
use App\Filament\Resources\ProductKeluar\Tables\ProductKeluarsTable;
use App\Models\ProductKeluar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductKeluarResource extends Resource
{
    protected static ?string $model = ProductKeluar::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUpTray;

    protected static ?string $recordTitleAttribute = 'qty_keluar';

    public static function form(Schema $schema): Schema
    {
        return ProductKeluarForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductKeluarsTable::configure($table);
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
            'index' => ListProductKeluars::route('/'),
            'create' => CreateProductKeluar::route('/create'),
            'edit' => EditProductKeluar::route('/{record}/edit'),
        ];
    }
}
