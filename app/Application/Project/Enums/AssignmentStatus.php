<?php

namespace App\Application\Project\Enums;

enum AssignmentStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case REJECTED_BY_EDUCATOR = 'rejected_by_educator';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-warning',
            self::ACCEPTED => 'bg-primary',
            self::REJECTED => 'bg-danger',
            self::IN_PROGRESS => 'bg-info',
            self::COMPLETED => 'bg-success',
            self::CANCELLED => 'bg-secondary',
            self::REJECTED_BY_EDUCATOR => 'bg-dark',
        };
    }

    public function trans(): string
    {
        return match ($this) {
            self::PENDING => __('lang.task_assignment_status.pending'),
            self::ACCEPTED => __('lang.task_assignment_status.accepted'),
            self::REJECTED => __('lang.task_assignment_status.rejected'),
            self::IN_PROGRESS => __('lang.task_assignment_status.in_progress'),
            self::COMPLETED => __('lang.task_assignment_status.completed'),
            self::CANCELLED => __('lang.task_assignment_status.cancelled'),
            self::REJECTED_BY_EDUCATOR => __('lang.task_assignment_status.rejected_by_educator'),
        };
    }

    public function isCompleted(): bool
    {
        return $this === self::COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this === self::REJECTED;
    }

    public function isRejectedByEducator(): bool
    {
        return $this === self::REJECTED_BY_EDUCATOR;
    }

    public static function defaultStatus()
    {
        return self::PENDING;
    }
}
