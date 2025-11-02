<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class FeaturesWidget extends Widget
{
    protected string $view = 'filament.widgets.features-widget';

    protected int|string|array $columnSpan = 'full';
    
    protected static ?int $sort = 3;
}
