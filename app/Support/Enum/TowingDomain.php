<?php


namespace App\Support\Enum;

enum TowingDomain: string
{
    case INSIDE = 'inside_city';
    case OUTSIDE = 'between_cities';
}
