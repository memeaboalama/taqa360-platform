<?php

namespace App\Application\Project\Enums;

enum ProjectStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-warning',
            self::IN_PROGRESS => 'bg-primary',
            self::COMPLETED => 'bg-success',
            self::CANCELED => 'bg-danger',
        };
    }

    public function trans(): string
    {
        return match ($this) {
            self::PENDING => __('lang.project_task_status.pending'),
            self::IN_PROGRESS => __('lang.project_task_status.in_progress'),
            self::COMPLETED => __('lang.project_task_status.completed'),
            self::CANCELED => __('lang.project_task_status.canceled'),
        };
    }


    public function isCanceled(): bool
    {
        return $this === self::CANCELED;
    }

    public function isActive(): bool
    {
        return in_array($this, [self::PENDING, self::IN_PROGRESS]);
    }

    public function isCompleted(): bool
    {
        return $this === self::COMPLETED;
    }

    public static function defaultStatus()
    {
        return self::PENDING;
    }
}
