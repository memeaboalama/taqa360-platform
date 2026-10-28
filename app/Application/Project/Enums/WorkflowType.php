<?php

namespace App\Application\Project\Enums;

enum WorkflowType: string
{
    case DIRECT_ASSIGNMENT = 'direct_assignment';
    case PUBLIC_TENDER = 'public_tender';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public function trans(): string
    {
        return match ($this) {
            self::DIRECT_ASSIGNMENT => __('lang.workflow_type.direct_assignment'),
            self::PUBLIC_TENDER => __('lang.workflow_type.public_tender'),
        };
    }
}
