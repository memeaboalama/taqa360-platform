<?php

namespace App\Application\Project\Enums;

enum CreationType: string
{
    case STANDALONE = 'standalone';
    case PUBLIC = 'public';
    case FROM_REQUEST = 'from_request';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public function trans(): string
    {
        return match ($this) {
            self::STANDALONE => __('lang.creation_type.standalone'),
            self::PUBLIC => __('lang.creation_type.public'),
            self::FROM_REQUEST => __('lang.creation_type.from_request'),
        };
    }

    public static function defaultStatus()
    {
        return self::STANDALONE;
    }
}
