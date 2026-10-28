<?php


namespace App\Support\Enum;

enum OTPSettingStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
