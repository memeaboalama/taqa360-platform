<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\Car\Brand;
use App\Domain\AppBase\Models\Location\City;
use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\AppBase\Models\Payment;
use Domain\MarketPlace\Models\Day;

class PaymentRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }
}
