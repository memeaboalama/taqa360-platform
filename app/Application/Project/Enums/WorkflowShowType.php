<?php

namespace App\Application\Project\Enums;

enum WorkflowShowType: string
{
    case INITIAL = 'initial';
    case PUBLIC_TENDER = 'public_tender';
    case PRIVATE = 'private';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public function trans(): string
    {
        return match ($this) {
            self::INITIAL => __('lang.workflow_show_type.initial'),
            self::PUBLIC_TENDER => __('lang.workflow_show_type.public_tender'),
            self::PRIVATE => __('lang.workflow_show_type.private'),
        };
    }
}
