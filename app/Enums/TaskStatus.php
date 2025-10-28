<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum TaskStatus: string implements HasLabel, HasColor, HasIcon
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case ARCHIVED = 'archived';
    case REVIEW = 'review';


    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
            self::ARCHIVED => 'Archived',
            self::REVIEW => 'In Review',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::IN_PROGRESS => 'info',
            self::COMPLETED => 'success',
            self::ARCHIVED => 'dark',
            self::REVIEW => 'warning',
        };
    }

    public function getIcon(): string|BackedEnum|null
    {
        return match ($this) {
            self::PENDING => Heroicon::OutlinedClock,
            self::IN_PROGRESS => Heroicon::OutlinedArrowRightOnRectangle,
            self::COMPLETED => Heroicon::OutlinedCheckCircle,
            self::ARCHIVED => Heroicon::OutlinedArchiveBox,
            self::REVIEW => Heroicon::OutlinedEye,
        };
    }
}
