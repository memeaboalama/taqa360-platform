<?php

namespace App\Support\Enum\Edu;
enum EducationLevelEnum: string
{
    case HIGH_SCHOOL = 'high_school';
    case DIPLOMA = 'diploma';
    case BACHELOR = 'bachelor';
    case MASTER = 'master';
    case PHD = 'phd';

    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }

    public function trans(): string
    {
        return match ($this) {
            self::HIGH_SCHOOL =>__('educator::builder.enums.high_school'),
            self::DIPLOMA => __('educator::builder.enums.diploma'),
            self::BACHELOR => __('educator::builder.enums.bachelor'),
            self::MASTER => __('educator::builder.enums.master'),
            self::PHD => __('educator::builder.enums.phd')
        };
    }
}
