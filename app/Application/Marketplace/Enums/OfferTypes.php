<?php

namespace App\Application\Marketplace\Enums;

enum OfferTypes: string
{
    case Percent = 'percent';
    case Flat = 'flat';
   
    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }

    public function color(): string
    {
        return match ($this) {
            self::Percent => 'bg-success',
            self::Flat => 'bg-primary',
        };
    }

}
