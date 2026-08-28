<?php

namespace App\Filament\Admin\Resources\Staffs;

use App\Filament\Admin\Resources\Staffs\Pages\CreateStaff;
use App\Filament\Admin\Resources\Staffs\Pages\EditStaff;
use App\Filament\Admin\Resources\Staffs\Pages\ListStaffs;
use App\Filament\Admin\Resources\Staffs\Schemas\StaffForm;
use App\Filament\Admin\Resources\Staffs\Tables\StaffsTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class StaffResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Kelola Staff';

    protected static ?string $modelLabel = 'Staff';

    protected static ?string $pluralModelLabel = 'Kelola Staff';

    protected static ?string $recordTitleAttribute = 'email';

    public static function form(Schema $schema): Schema
    {
        return StaffForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaffsTable::configure($table);
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->whereHas('profile', function ($q) {
            $q->whereIn('role', ['admin', 'kasir', 'stok', 'store']);
        });
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
            'index' => ListStaffs::route('/'),
            'create' => CreateStaff::route('/create'),
            'edit' => EditStaff::route('/{record}/edit'),
        ];
    }
}
