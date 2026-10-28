<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\AdditionGroup;

use App\Domain\MarketPlace\Repositories\Merchant\AdditionGroupRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class FetchAdditionGroupAction
{


    public function __construct(protected AdditionGroupRepository $modelR)
    {
        $this->modelR = $modelR;
    }



    public function handle(int $merchant_id): \Illuminate\Database\Eloquent\Builder
    {
        return $this->modelR->getCategoriesByMerchantId($merchant_id);
    }
}
