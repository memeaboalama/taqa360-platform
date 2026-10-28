<?php


namespace App\Support\Enum;

enum PaymentMethod: string
{
    case MADA = 'mada';
    case APPLE = 'apple';
    case STC = 'stc';
    case COD = 'COD';
    case ONLINE_LATER = 'online_later';
}
