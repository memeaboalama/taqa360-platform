<?php

namespace App\Application\Project\Enums;

enum BidStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case ASSIGNED = 'assigned';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'في الانتظار',
            self::APPROVED => 'مقبول',
            self::ASSIGNED => 'معين',
            self::REJECTED => 'مرفوض',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'blue',
            self::APPROVED => 'green',
            self::ASSIGNED => 'purple',
            self::REJECTED => 'red',
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::ASSIGNED, self::REJECTED]);
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function isAccepted(): bool
    {
        return $this === self::APPROVED;
    }

    public function isRejected(): bool
    {
        return $this === self::REJECTED;
    }

    public static function defaultStatus()
    {
        return self::PENDING;
    }
}
