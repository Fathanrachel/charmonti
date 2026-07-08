<?php

namespace App\Filament\Owner\Resources\BahanMasuks;

use App\Filament\Owner\Resources\BahanMasuks\Pages\CreateBahanMasuk;
use App\Filament\Owner\Resources\BahanMasuks\Pages\EditBahanMasuk;
use App\Filament\Owner\Resources\BahanMasuks\Pages\ListBahanMasuks;
use App\Filament\Resources\BahanMasuk\Schemas\BahanMasukForm;
use App\Filament\Resources\BahanMasuk\Tables\BahanMasukTable;
use App\Models\BahanMasuk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BahanMasukResource extends Resource
{
    protected static ?string $model = BahanMasuk::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxIn;

    protected static ?string $navigationLabel = 'Bahan Masuk';

    protected static ?string $modelLabel = 'Bahan Masuk';

    protected static ?string $pluralModelLabel = 'Bahan Masuk';

    protected static ?string $recordTitleAttribute = 'nama_bahan';

    public static function form(Schema $schema): Schema
    {
        return BahanMasukForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BahanMasukTable::configure($table);
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
