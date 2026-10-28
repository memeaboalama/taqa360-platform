<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\AdditonGroups;

use App\Domain\MarketPlace\Repositories\Merchant\AdditionGroupRepository;

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
