<?php

namespace App\Support\Enum;

enum ProductContentStatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SCHEDULED = 'scheduled';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => __('admin::lang.Active'),
            self::INACTIVE => __('admin::lang.Inactive'),
            self::SCHEDULED => __('admin::lang.Scheduled'),
            self::EXPIRED => __('admin::lang.Expired'),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
