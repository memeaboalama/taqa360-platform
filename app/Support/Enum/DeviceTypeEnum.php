<?php

namespace App\Support\Enum;

enum DeviceTypeEnum: string
{
    case DESKTOP = 'desktop';
    case TABLET = 'tablet';
    case MOBILE = 'mobile';

    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }

    public function label(): string
    {
        return match($this) {
            self::DESKTOP => __('admin::lang.Desktop'),
            self::TABLET => __('admin::lang.Tablet'),
            self::MOBILE => __('admin::lang.Mobile'),
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::DESKTOP => 'bi-pc-display',
            self::TABLET => 'bi-tablet',
            self::MOBILE => 'bi-phone',
        };
    }
}
