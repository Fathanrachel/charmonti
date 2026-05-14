<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Material')
                    ->required()
                    ->maxLength(255),

                TextInput::make('stock')
                    ->label('Stok saat ini')
                    ->required()
                    ->numeric()
                    ->minValue(0),

                TextInput::make('unit')
                    ->label('Satuan')
                    ->required()
                    ->placeholder('Contoh: pcs, meter, gram'),
            ]);
    }
}
