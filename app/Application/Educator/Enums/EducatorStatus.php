<?php

namespace App\Application\Educator\Enums;

enum EducatorStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING = 'pending';
    case REJECTED = 'rejected';
    case SUSPENDED = 'suspended';
    case BANNED = 'banned';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'bg-success',
            self::INACTIVE => 'bg-secondary',
            self::PENDING => 'bg-warning',
            self::REJECTED => 'bg-danger',
            self::SUSPENDED => 'bg-danger',
            self::BANNED => 'bg-danger',
        };
    }

    public function trans(): string
    {
        return match ($this) {
            self::ACTIVE => __('lang.educator_status.active'),
            self::INACTIVE => __('lang.educator_status.inactive'),
            self::PENDING => __('lang.educator_status.pending'),
            self::REJECTED => __('lang.educator_status.rejected'),
            self::SUSPENDED => __('lang.educator_status.suspended'),
            self::BANNED => __('lang.educator_status.banned') ?? 'Banned',
        };
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function isSuspended(): bool
    {
        return $this === self::SUSPENDED || $this === self::BANNED;
    }
}
