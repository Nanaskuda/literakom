<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;


class CustomAccountWidget extends Widget
{
    protected static string $view = 'filament.widgets.custom-account-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -3;

    protected static bool $isLazy = true;
}
