<?php

namespace App\Filament\Admin\Resources\Expeditions\Pages;

use App\Filament\Admin\Resources\Expeditions\ExpeditionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditExpedition extends EditRecord
{
    protected static string $resource = ExpeditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
