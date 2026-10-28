<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\AdditonGroups;

use App\Domain\MarketPlace\Repositories\Merchant\AdditionGroupRepository;

class FetchSingleAdditionGroupAction
{
    public function __construct(protected AdditionGroupRepository $modelR)
    {
        $this->modelR = $modelR;
    }



    public function handle(int $id, int $merchant_id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->modelR->findByCols(['merchant_id' => $merchant_id, 'id' => $id]);
    }
}
