<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Choice;

use App\Domain\MarketPlace\Repositories\Merchant\ChoiceRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class FetchChoiceAction
{


    public function __construct(protected ChoiceRepository $modelR)
    {
        $this->modelR = $modelR;
    }



    public function handle(int $merchant_id): \Illuminate\Database\Eloquent\Builder
    {
        return $this->modelR->getCategoriesByMerchantId($merchant_id);
    }
}
