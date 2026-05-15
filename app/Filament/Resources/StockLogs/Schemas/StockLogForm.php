<?php

namespace App\Filament\Resources\StockLogs\Schemas;

use App\Models\Material;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StockLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('material_id')
                    ->label('Material')
                    ->options(Material::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),

                Select::make('type')
                    ->label('Tipe')
                    ->options([
                        'in' => 'Masuk (in)',
                        'out' => 'Keluar (out)',
                    ])
                    ->required(),

                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->required()
                    ->numeric()
                    ->minValue(1),

                Textarea::make('description')
                    ->label('Keterangan')
                    ->nullable()
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}