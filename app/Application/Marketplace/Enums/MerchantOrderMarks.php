<?php

namespace App\Application\Marketplace\Enums;

enum MerchantOrderMarks: string
{
    case WHITE = 'white';
    case YELLOW = 'yellow';
    case GREEN = 'green';
    case BLUE = 'blue';
    case RED = 'red';

    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }

    public function color(): string
    {
        return match ($this) {
            self::WHITE => 'bg-white',
            self::YELLOW => 'bg-warning',
            self::GREEN => 'bg-success',
            self::RED => 'bg-danger',
            self::BLUE => 'bg-info',
        };
    }


    public function trans(): string
    {
        return match ($this) {
            self::WHITE => __('lang.order_mark.white'),
            self::RED => __('lang.order_mark.red'),
            self::YELLOW => __('lang.order_mark.yellow'),
            self::GREEN => __('lang.order_mark.green'),
            self::BLUE => __('lang.order_mark.blue')
        };
    }
}
