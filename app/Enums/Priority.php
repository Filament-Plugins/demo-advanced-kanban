<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Support\Htmlable;

enum Priority: string implements HasLabel, HasColor, HasIcon
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::LOW => 'gray',
            self::MEDIUM => 'warning',
            self::HIGH => 'danger',
        };
    }

    public function getIcon(): string|BackedEnum|null
    {
        return match ($this) {
            self::LOW => Heroicon::Minus,
            self::MEDIUM => Heroicon::Bars2,
            self::HIGH => Heroicon::Bars3,
        };
    }

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
        };
    }
}
