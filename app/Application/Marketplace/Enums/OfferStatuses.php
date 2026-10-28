<?php

namespace App\Application\Marketplace\Enums;

enum OfferStatuses: string
{
    case Active = 'active';
    case Inactive = 'inactive';
   
    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'bg-success',
            self::Inactive => 'bg-danger',
        };
    }


    public function trans(): string
    {
        return match ($this) {
            self::Active => __('lang.active'),
            self::Inactive => __('lang.inactive'),
        };
    }

}
