<?php


namespace App\Payment\ClickPay\Facades;


use Illuminate\Support\Facades\Facade;

class paypage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'paypage';
    }
}
