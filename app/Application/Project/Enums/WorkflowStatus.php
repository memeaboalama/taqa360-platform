<?php

namespace App\Application\Project\Enums;

enum WorkflowStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case PENDING_ASSIGNMENT = 'pending_assignment';
    case OPEN_FOR_BIDS = 'open_for_bids';
    case AWAITING_SELECTION = 'awaiting_selection';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public function trans(): string
    {
        return match ($this) {
            self::DRAFT => __('lang.project_status.draft'),
            self::PENDING => __('lang.project_status.pending'),
            self::ACTIVE => __('lang.project_status.active'),
            self::IN_PROGRESS => __('lang.project_status.in_progress'),
            self::COMPLETED => __('lang.project_status.completed'),
            self::CANCELLED => __('lang.project_status.cancelled'),
            self::PENDING_ASSIGNMENT => __('lang.project_status.pending_assignment'),
            self::OPEN_FOR_BIDS => __('lang.project_status.open_for_bids'),
            self::AWAITING_SELECTION => __('lang.project_status.awaiting_selection'),
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
}

