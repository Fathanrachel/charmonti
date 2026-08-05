<?php

namespace App\Filament\Admin\Resources\BahanMasuks;

use App\Filament\Admin\Resources\BahanMasuks\Pages\CreateBahanMasuk;
use App\Filament\Admin\Resources\BahanMasuks\Pages\EditBahanMasuk;
use App\Filament\Admin\Resources\BahanMasuks\Pages\ListBahanMasuks;
use App\Filament\Resources\BahanMasuk\Schemas\BahanMasukForm;
use App\Filament\Resources\BahanMasuk\Tables\BahanMasukTable;
use App\Models\BahanMasuk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Model;

class BahanMasukResource extends Resource
{
    protected static ?string $model = BahanMasuk::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $navigationLabel = 'Bahan Masuk';

    protected static ?string $modelLabel = 'Bahan Masuk';

    protected static ?string $pluralModelLabel = 'Bahan Masuk';

    protected static ?string $recordTitleAttribute = 'id';

    public static function getRecordTitle(?Model $record): ?string
    {
        if (! $record) {
            return null;
        }

        /** @var \App\Models\BahanMasuk $record */
        $nama = $record->bahan?->nama_bahan;

        return $nama ? "Bahan Masuk #{$record->id} ({$nama})" : "Bahan Masuk #{$record->id}";
    }

    public static function form(Schema $schema): Schema
    {
        return BahanMasukForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BahanMasukTable::configure($table);
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
            'index' => ListBahanMasuks::route('/'),
            'create' => CreateBahanMasuk::route('/create'),
            'edit' => EditBahanMasuk::route('/{record}/edit'),
        ];
    }
}
