<?php

namespace App\Support\Enum;

enum ClickActionTypeEnum: string
{
    case NONE = 'none';
    case URL = 'url';
    case PRODUCT = 'product';
    case CATEGORY = 'category';
    case MERCHANT = 'merchant';

    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }

    public function label(): string
    {
        return match($this) {
            self::NONE => __('admin::lang.No Action'),
            self::URL => __('admin::lang.URL Link'),
            self::PRODUCT => __('admin::lang.Product Link'),
            self::CATEGORY => __('admin::lang.Category Link'),
            self::MERCHANT => __('admin::lang.Merchant Link'),
        };
    }

    public function requiresSearch(): bool
    {
        return in_array($this, [
            self::PRODUCT,
            self::CATEGORY,
            self::MERCHANT,
        ]);
    }

    public function requiresInput(): bool
    {
        return in_array($this, [
            self::URL,
        ]);
    }
}
