<?php

namespace App\Application\Educator\Enums;

enum AcademicDegreeEnum: string
{
    // Keep this list aligned with App\Support\Enum\Edu\EducationLevelEnum, which is
    // what AcademicMajorRequest validates against and what the provider profile
    // builder offers. A value that passes validation but is missing here makes
    // Eloquent throw a ValueError while casting, which killed the whole admin
    // "qualifications and achievements" tab.
    case HIGH_SCHOOL = 'high_school';
    case DIPLOMA = 'diploma';
    case BACHELOR = 'bachelor';
    case MASTER = 'master';
    case DOCTRATE = 'doctorate';
    case PHD = 'phd';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public function trans(): string
    {
        return match ($this) {
            self::HIGH_SCHOOL => __('lang.academic_degree.high_school'),
            self::DIPLOMA => __('lang.academic_degree.diploma'),
            self::BACHELOR => __('lang.academic_degree.bachelor'),
            self::MASTER => __('lang.academic_degree.master'),
            self::DOCTRATE => __('lang.academic_degree.doctorate'),
            self::PHD => __('lang.academic_degree.phd'),
        };
    }

    public function defaultDeqree()
    {
        return self::BACHELOR;
    }
}