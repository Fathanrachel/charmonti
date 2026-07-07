<?php

namespace App\Filament\Resources\Complaints\Pages;

use App\Filament\Resources\Complaints\ComplaintResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComplaint extends EditRecord
{
    protected static string $resource = ComplaintResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
