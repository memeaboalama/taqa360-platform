<?php

namespace App\Application\Project\Enums;

enum TaskStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

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
            self::CANCELLED => 'bg-danger',
        };
    }

    public function trans(): string
    {
        return match ($this) {
            self::PENDING => __('lang.project_task_status.pending'),
            self::IN_PROGRESS => __('lang.project_task_status.in_progress'),
            self::COMPLETED => __('lang.project_task_status.completed'),
            self::CANCELLED => __('lang.project_task_status.cancelled'),
        };
    }

    public function isCompleted(): bool
    {
        return $this === self::COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this === self::CANCELLED;
    }

    public static function defaultStatus()
    {
        return self::PENDING;
    }
}
