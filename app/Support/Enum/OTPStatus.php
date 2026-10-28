<?php


namespace App\Support\Enum;

enum OTPStatus: string
{
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case USED = 'used';
}
