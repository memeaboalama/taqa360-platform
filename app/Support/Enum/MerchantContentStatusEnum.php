<?php

namespace App\Support\Enum;

enum MerchantContentStatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SCHEDULED = 'scheduled';
    case EXPIRED = 'expired';

    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => __('admin::lang.Active'),
            self::INACTIVE => __('admin::lang.Inactive'),
            self::SCHEDULED => __('admin::lang.Scheduled'),
            self::EXPIRED => __('admin::lang.Expired'),
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'secondary',
            self::SCHEDULED => 'info',
            self::EXPIRED => 'danger',
        };
    }
}
