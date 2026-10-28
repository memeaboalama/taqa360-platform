<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Choice;

use App\Domain\MarketPlace\Repositories\Merchant\ChoiceRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class FetchSingleChoiceAction
{


    public function __construct(protected ChoiceRepository $modelR)
    {
        $this->modelR = $modelR;
    }



    public function handle(int $id, int $merchant_id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->modelR->findByCols(['merchant_id' => $merchant_id, 'id' => $id]);
    }
}
