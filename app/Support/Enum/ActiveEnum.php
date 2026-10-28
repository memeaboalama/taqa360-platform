<?php

namespace App\Support\Enum;

enum ActiveEnum : string
{
    case ACTIVE = 'active';

    case INACTIVE = 'inactive';

    case SUSPENDED = 'suspended';

    case BANNED = 'banned';

    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'bg-success',
            self::INACTIVE => 'bg-danger',
            self::SUSPENDED => 'bg-warning',
            self::BANNED => 'bg-danger',
        };
    }


    public function trans(): string
    {
        return match ($this) {
            self::ACTIVE => __('marketplace::home.Active'),
            self::INACTIVE => __('marketplace::home.Inactive'),
            self::SUSPENDED => __('Suspended'),
            self::BANNED => __('Banned'),
        };
    }
}
