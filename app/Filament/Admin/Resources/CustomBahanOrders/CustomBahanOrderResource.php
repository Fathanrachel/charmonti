<?php

namespace App\Filament\Admin\Resources\CustomBahanOrders;

use App\Filament\Admin\Resources\CustomBahanOrders\Pages\CreateCustomBahanOrder;
use App\Filament\Admin\Resources\CustomBahanOrders\Pages\EditCustomBahanOrder;
use App\Filament\Admin\Resources\CustomBahanOrders\Pages\ListCustomBahanOrders;
use App\Filament\Resources\CustomBahanOrders\Schemas\CustomBahanOrderForm;
use App\Filament\Resources\CustomBahanOrders\Tables\CustomBahanOrdersTable;
use App\Models\CustomBahanOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomBahanOrderResource extends Resource
{
    protected static ?string $model = CustomBahanOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    public static function getRecordTitle(?\Illuminate\Database\Eloquent\Model $record): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        if (! $record) return null;
        $warna = $record->warna ? ucfirst($record->warna) : 'Custom';
        return "Gelang Custom #{$record->id} ({$warna})";
    }

    public static function form(Schema $schema): Schema
    {
        return CustomBahanOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomBahanOrdersTable::configure($table);
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
            'index' => ListCustomBahanOrders::route('/'),
            'create' => CreateCustomBahanOrder::route('/create'),
            'edit' => EditCustomBahanOrder::route('/{record}/edit'),
        ];
    }
}
