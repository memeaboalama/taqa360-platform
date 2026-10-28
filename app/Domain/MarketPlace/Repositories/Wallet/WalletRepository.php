<?php

namespace App\Domain\MarketPlace\Repositories\Wallet;

use App\Support\Repositories\Main\EloquentMainRepository;
use Bavix\Wallet\Models\Wallet;


class WalletRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public array $months;

    public function __construct(Wallet $model)
    {   
        parent::__construct($model);
    }
}
