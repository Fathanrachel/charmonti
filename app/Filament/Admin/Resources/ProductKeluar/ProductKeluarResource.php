<?php

namespace App\Filament\Admin\Resources\ProductKeluar;

use App\Filament\Admin\Resources\ProductKeluar\Pages\CreateProductKeluar;
use App\Filament\Admin\Resources\ProductKeluar\Pages\EditProductKeluar;
use App\Filament\Admin\Resources\ProductKeluar\Pages\ListProductKeluars;
use App\Filament\Resources\ProductKeluar\Schemas\ProductKeluarForm;
use App\Filament\Resources\ProductKeluar\Tables\ProductKeluarsTable;
use App\Models\ProductKeluar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Model;

class ProductKeluarResource extends Resource
{
    protected static ?string $model = ProductKeluar::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUpTray;

    protected static ?string $recordTitleAttribute = 'id';

    public static function getRecordTitle(?Model $record): ?string
    {
        if (! $record) {
            return null;
        }

        /** @var \App\Models\ProductKeluar $record */
        $nama = $record->product?->product_name ?? $record->productMasuk?->product?->product_name;

        return $nama ? "Produk Keluar #{$record->id} ({$nama})" : "Produk Keluar #{$record->id}";
    }

    public static function form(Schema $schema): Schema
    {
        return ProductKeluarForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductKeluarsTable::configure($table);
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
            'index' => ListProductKeluars::route('/'),
            'create' => CreateProductKeluar::route('/create'),
            'edit' => EditProductKeluar::route('/{record}/edit'),
        ];
    }
}
