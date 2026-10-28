<?php


namespace App\Support\Enum;

use Support\Traits\Enums\EnumConverter;

enum MerchantOrderStatus: string
{
    use EnumConverter;

    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case START_WORK = 'start-work';
    case ON_WAY = 'on-way';

    case COMPLETE = 'completed';
    case CANCEL = 'cancel';
}
