<?php

namespace App\Support\Enum;

enum BannerStatusEnum: string
{
    case DRAFT = 'draft';
    case SCHEDULED = 'scheduled';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case EXPIRED = 'expired';

    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }

    public function label(): string
    {
        return match($this) {
            self::DRAFT => __('admin::lang.Draft'),
            self::SCHEDULED => __('admin::lang.Scheduled'),
            self::ACTIVE => __('admin::lang.Active'),
            self::INACTIVE => __('admin::lang.Inactive'),
            self::EXPIRED => __('admin::lang.Expired'),
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::DRAFT => 'badge-secondary',
            self::SCHEDULED => 'badge-info',
            self::ACTIVE => 'badge-success',
            self::INACTIVE => 'badge-warning',
            self::EXPIRED => 'badge-danger',
        };
    }

    public function isDisplayable(): bool
    {
        return in_array($this, [self::ACTIVE, self::SCHEDULED]);
    }
}
