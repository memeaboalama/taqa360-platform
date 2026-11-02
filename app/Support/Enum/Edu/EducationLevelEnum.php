<?php

namespace App\Support\Enum\Edu;

enum EducationLevelEnum: string
{
    case ASSOCIATE = 'associate';
    case BACHELOR = 'bachelor';
    case PROFESSIONAL = 'professional';
    case GRADUATE = 'graduate';

    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }

    public function trans(): string
    {
        return match ($this) {
            self::ASSOCIATE =>__('educator::builder.enums.associate'),
            self::BACHELOR =>__('educator::builder.enums.bachelor'),
            self::PROFESSIONAL =>__('educator::builder.enums.professional'),
            self::GRADUATE =>__('educator::builder.enums.graduate')
        };
    }
}