<?php

namespace App\Filament\Admin\Resources\ComplaintCategories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ComplaintCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kategori Komplain')
                    ->placeholder('Misal: Barang Rusak / Cacat')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Deskripsi Kategori')
                    ->placeholder('Penjelasan mengenai kategori komplain ini...')
                    ->rows(3),
            ]);
    }
}
