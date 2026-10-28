<?php


namespace App\Support\Enum\Elm;

enum ElmOrderStatuses: string
{   
    case COMPLETED = '0';
    case NEW_ORDER = '1';
    case ACCEPTED_BY_MERCHANT = '2';
    case DRIVER_ASSIGNED = '3';
    case EXECUTED = '4';
    case CANCELLED = '5';
    case CANCELLED_BY_CLIENT = '8';
    case REJECTED_BY_MERCHANT = '6';
    case REJECTED_BY_DRIVER = '7';


    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }
}

