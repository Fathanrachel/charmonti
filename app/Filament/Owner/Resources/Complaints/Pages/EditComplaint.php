<?php

namespace App\Filament\Owner\Resources\Complaints\Pages;

use App\Filament\Owner\Resources\Complaints\ComplaintResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComplaint extends EditRecord
{
    protected static string $resource = ComplaintResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
