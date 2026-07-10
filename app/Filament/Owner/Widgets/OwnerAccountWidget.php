<?php

namespace App\Filament\Owner\Widgets;

use Filament\Widgets\AccountWidget as BaseAccountWidget;

class OwnerAccountWidget extends BaseAccountWidget
{
    protected static ?int $sort = 0;
    protected int | string | array $columnSpan = 'full';
    protected string $view = 'filament.widgets.custom-account-widget';
}
