<?php

namespace App\Payment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;


/**
 * @method static pay(array $data,Model $model)
 * @method static paymentStatus(array $postFields)
 */
class Payment extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'Payment';
    }

}
