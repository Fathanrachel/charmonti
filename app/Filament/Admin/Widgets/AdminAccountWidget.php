<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\AccountWidget as BaseAccountWidget;

class AdminAccountWidget extends BaseAccountWidget
{
    protected int | string | array $columnSpan = 'full';
    protected string $view = 'filament.widgets.custom-account-widget';
}
