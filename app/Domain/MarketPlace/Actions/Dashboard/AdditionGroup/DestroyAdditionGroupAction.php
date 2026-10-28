<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\AdditionGroup;

use App\Domain\MarketPlace\Repositories\Merchant\AdditionGroupRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantAdditionGroupRepository;

class DestroyAdditionGroupAction
{


    public function __construct(protected AdditionGroupRepository $modelR)
    {
        $this->modelR = $modelR;
    }


    public function handle(int $id): bool
    {
        return $this->modelR->destroy($id);
    }
}
