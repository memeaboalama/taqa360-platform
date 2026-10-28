<?php

namespace App\Support\Enum;

enum ProductQueryTypeEnum: string
{
    case PRESET = 'preset';
    case CUSTOM = 'custom';
    case EXPERT = 'expert';

    public function label(): string
    {
        return match($this) {
            self::PRESET => __('admin::lang.Preset Template'),
            self::CUSTOM => __('admin::lang.Custom Query'),
            self::EXPERT => __('admin::lang.Expert Mode'),
        };
    }
}
