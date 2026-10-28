<?php

namespace App\Support\Enum;

enum PaymentStatusEnum: string
{
    case PAID = 'paid';
    case UNPAID = 'unpaid';
    case PARTIALLY_PAID = 'partially_paid';
    case PARTIAL = 'partial';

    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }
}
