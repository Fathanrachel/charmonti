<?php

namespace App\Filament\Owner\Resources\Expeditions\Pages;

use App\Filament\Owner\Resources\Expeditions\ExpeditionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExpeditions extends ListRecords
{
    protected static string $resource = ExpeditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
