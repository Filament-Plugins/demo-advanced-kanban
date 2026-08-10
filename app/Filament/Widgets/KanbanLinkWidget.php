<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class KanbanLinkWidget extends Widget
{
    protected string $view = 'filament.widgets.kanban-link-widget';

    protected static ?int $sort = -3;

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;
}
