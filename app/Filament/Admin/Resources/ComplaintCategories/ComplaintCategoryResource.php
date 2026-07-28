<?php

namespace App\Filament\Admin\Resources\ComplaintCategories;

use App\Filament\Admin\Resources\ComplaintCategories\Pages\CreateComplaintCategory;
use App\Filament\Admin\Resources\ComplaintCategories\Pages\EditComplaintCategory;
use App\Filament\Admin\Resources\ComplaintCategories\Pages\ListComplaintCategories;
use App\Filament\Admin\Resources\ComplaintCategories\Schemas\ComplaintCategoryForm;
use App\Filament\Admin\Resources\ComplaintCategories\Tables\ComplaintCategoriesTable;
use App\Models\ComplaintCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ComplaintCategoryResource extends Resource
{
    protected static ?string $model = ComplaintCategory::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Kategori Komplain';

    protected static ?string $modelLabel = 'Kategori Komplain';

    protected static ?string $pluralModelLabel = 'Kategori Komplain';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ComplaintCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComplaintCategoriesTable::configure($table);
    }

    public static function canViewAny(): bool
    {
        $role = auth()->user()?->profile?->role;
        return in_array($role, ['admin', 'kasir']);
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
            'index' => ListComplaintCategories::route('/'),
            'create' => CreateComplaintCategory::route('/create'),
            'edit' => EditComplaintCategory::route('/{record}/edit'),
        ];
    }
}
