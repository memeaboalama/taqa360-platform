<?php

namespace App\Domain\MarketPlace\Repositories\Wallet;

use App\Domain\AppBase\Models\WalletTransaction;
use App\Support\Repositories\Main\EloquentMainRepository;


class WalletTransactionRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public array $months;

    public function __construct(WalletTransaction $model)
    {   
        parent::__construct($model);
    }
}
