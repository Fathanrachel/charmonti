<?php

namespace App\Filament\Admin\Resources\Complaints;

use App\Filament\Admin\Resources\Complaints\Pages\EditComplaint;
use App\Filament\Admin\Resources\Complaints\Pages\ListComplaints;
use App\Filament\Resources\Complaints\Schemas\ComplaintForm;
use App\Filament\Resources\Complaints\Tables\ComplaintsTable;
use App\Models\Complaint;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ComplaintResource extends Resource
{
    protected static ?string $model = Complaint::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static ?string $navigationLabel = 'Komplain';
    protected static ?string $modelLabel = 'Komplain';
    protected static ?string $pluralModelLabel = 'Komplain';

    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return ComplaintForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComplaintsTable::configure($table);
    }

    public static function canViewAny(): bool
    {
        $role = auth()->user()?->profile?->role;
        return in_array($role, ['admin', 'kasir']);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComplaints::route('/'),
            'edit' => EditComplaint::route('/{record}/edit'),
        ];
    }
}
