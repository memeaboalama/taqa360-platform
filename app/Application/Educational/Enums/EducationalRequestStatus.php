<?php

namespace App\Application\Educational\Enums;

enum EducationalRequestStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case ASSIGNED = 'assigned';
    case REJECTED = 'rejected';
    case IN_PROGRESS = 'in-progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public function trans(): string
    {
        return match ($this) {
            self::DRAFT => __('lang.educational_request.status.draft'),
            self::APPROVED => __('lang.educational_request.status.approved'),
            self::PENDING => __('lang.educational_request.status.pending'),
            self::ASSIGNED => __('lang.educational_request.status.assigned'),
            self::REJECTED => __('lang.educational_request.status.rejected'),
            self::IN_PROGRESS => __('lang.educational_request.status.in_progress'),
            self::COMPLETED => __('lang.educational_request.status.completed'),
            self::CANCELLED => __('lang.educational_request.status.cancelled'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-gray-400',
            self::PENDING => 'bg-yellow-400',
            self::APPROVED => 'bg-green-400',
            self::ASSIGNED => 'bg-blue-500',
            self::REJECTED => 'bg-red-500',
            self::IN_PROGRESS => 'bg-purple-500',
            self::COMPLETED => 'bg-green-500',
            self::CANCELLED => 'bg-red-700',
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::COMPLETED, self::CANCELLED, self::REJECTED]);
    }
}
