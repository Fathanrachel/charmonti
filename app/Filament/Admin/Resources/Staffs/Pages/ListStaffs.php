<?php

namespace App\Filament\Admin\Resources\Staffs\Pages;

use App\Filament\Admin\Resources\Staffs\StaffResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStaffs extends ListRecords
{
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Staff'),
        ];
    }
}
