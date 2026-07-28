<?php

namespace App\Filament\Admin\Resources\Staffs\Schemas;

use App\Filament\Admin\Resources\Staffs\Pages\CreateStaff;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('profile.name')
                    ->label('Nama Staff')
                    ->placeholder('Masukkan nama lengkap staff')
                    ->required(),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->placeholder('contoh@charmonti.com')
                    ->required()
                    ->unique('users', 'email', ignoreRecord: true),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->placeholder('Minimal 6 karakter')
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn ($livewire) => $livewire instanceof CreateStaff),

                TextInput::make('profile.phone')
                    ->label('Nomor HP / WA')
                    ->placeholder('081234567890')
                    ->tel(),

                Select::make('profile.role')
                    ->label('Role Staff')
                    ->options([
                        'kasir' => 'Kasir',
                        'store' => 'Staff Toko (Store)',
                        'admin' => 'Admin',
                    ])
                    ->native(false)
                    ->default('kasir')
                    ->required(),
            ]);
    }
}
