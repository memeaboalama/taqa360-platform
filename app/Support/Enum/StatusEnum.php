<?php


namespace App\Support\Enum;

enum StatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case DRAFT = 'draft';
    case REJECTED = 'rejected';

    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => __('admin::lang.Active'),
            self::INACTIVE => __('admin::lang.Inactive'),
            self::DRAFT => __('admin::lang.Draft'),
            self::REJECTED => __('admin::lang.Rejected'),
        };
    }
}
