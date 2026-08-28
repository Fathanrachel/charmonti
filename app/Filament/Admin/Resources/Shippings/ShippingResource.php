<?php

namespace App\Filament\Admin\Resources\Shippings;

use App\Filament\Admin\Resources\Shippings\Pages\CreateShipping;
use App\Filament\Admin\Resources\Shippings\Pages\EditShipping;
use App\Filament\Admin\Resources\Shippings\Pages\ListShippings;
use App\Filament\Resources\Shippings\Schemas\ShippingForm;
use App\Filament\Resources\Shippings\Tables\ShippingsTable;
use App\Models\Shipping;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ShippingResource extends Resource
{
    protected static ?string $model = Shipping::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    protected static ?string $modelLabel = 'Pengiriman';

    protected static ?string $pluralModelLabel = 'Pengiriman';

    protected static ?string $navigationLabel = 'Pengiriman';

    public static function getRecordTitle(?\Illuminate\Database\Eloquent\Model $record): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        if (!$record) return null;
        $customerName = $record->order?->profile?->name ?? 'Pelanggan';
        $key = $record->getKey();
        $resi = $record->tracking_number ? " [Resi: {$record->tracking_number}]" : '';
        return "Pengiriman #{$key} - {$customerName} (Pesanan #{$record->order_id}){$resi}";
    }

    public static function form(Schema $schema): Schema
    {
        return ShippingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShippingsTable::configure($table);
    }

    public static function canViewAny(): bool
    {
        $role = auth()->user()?->profile?->role;
        return in_array($role, ['admin', 'kasir', 'stok', 'store']);
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
            'index' => ListShippings::route('/'),
            'create' => CreateShipping::route('/create'),
            'edit' => EditShipping::route('/{record}/edit'),
        ];
    }
}
