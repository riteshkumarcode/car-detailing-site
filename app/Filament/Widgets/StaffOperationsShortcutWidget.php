<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class StaffOperationsShortcutWidget extends Widget
{
    protected static ?int $sort = 2;
    protected static string $view = 'filament.widgets.staff-operations-shortcut-widget';
    protected int | string | array $columnSpan = 'full';
}
