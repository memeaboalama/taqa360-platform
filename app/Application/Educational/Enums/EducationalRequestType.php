<?php

namespace App\Application\Educational\Enums;

enum EducationalRequestType: string
{
    case GENERAL = 'general';
    case TASK = 'task';
    case PROJECT = 'project';
    case RESEARCH = 'research';
    case EXPLANATION = 'explanation';
    case HOURLY = 'hourly';
    case FIXED = 'fixed';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public function trans(): string
    {
        return match ($this) {
            self::GENERAL => __('lang.educational_request.type.general'),
            self::TASK => __('lang.educational_request.type.task'),
            self::PROJECT => __('lang.educational_request.type.project'),
            self::RESEARCH => __('lang.educational_request.type.research'),
            self::EXPLANATION => __('lang.educational_request.type.explanation'),
            self::HOURLY => 'بالساعة',
            self::FIXED => 'مبلغ ثابت',
        };
    }
}
