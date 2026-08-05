<?php

namespace App\Filament\Admin\Resources\BahanKeluars;

use App\Filament\Admin\Resources\BahanKeluars\Pages\CreateBahanKeluar;
use App\Filament\Admin\Resources\BahanKeluars\Pages\EditBahanKeluar;
use App\Filament\Admin\Resources\BahanKeluars\Pages\ListBahanKeluars;
use App\Filament\Resources\BahanKeluar\Schemas\BahanKeluarForm;
use App\Filament\Resources\BahanKeluar\Tables\BahanKeluarTable;
use App\Models\BahanKeluar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Model;

class BahanKeluarResource extends Resource
{
    protected static ?string $model = BahanKeluar::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $navigationLabel = 'Bahan Keluar';

    protected static ?string $modelLabel = 'Bahan Keluar';

    protected static ?string $pluralModelLabel = 'Bahan Keluar';

    protected static ?string $recordTitleAttribute = 'id';

    public static function getRecordTitle(?Model $record): ?string
    {
        if (! $record) {
            return null;
        }

        /** @var \App\Models\BahanKeluar $record */
        $nama = $record->bahan?->nama_bahan ?? $record->bahanMasuk?->nama_bahan;

        return $nama ? "Bahan Keluar #{$record->id} ({$nama})" : "Bahan Keluar #{$record->id}";
    }

    public static function form(Schema $schema): Schema
    {
        return BahanKeluarForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BahanKeluarTable::configure($table);
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
            'index' => ListBahanKeluars::route('/'),
            'create' => CreateBahanKeluar::route('/create'),
            'edit' => EditBahanKeluar::route('/{record}/edit'),
        ];
    }
}
