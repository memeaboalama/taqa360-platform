<?php


namespace App\Support\Enum\Elm;

enum ElmDriverStatuses: string
{   
    case ACCEPTED = '0';
    case NEW = '1';
    case NEEDS_UPDATEING = '2';
    case REJECTED = '10';
    case REJECTED_BY_ADMIN = '3';


    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }
}

