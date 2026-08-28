<?php

namespace App\Filament\Admin\Resources\ComplaintCategories\Pages;

use App\Filament\Admin\Resources\ComplaintCategories\ComplaintCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateComplaintCategory extends CreateRecord
{
    protected static string $resource = ComplaintCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
