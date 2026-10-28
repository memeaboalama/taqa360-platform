<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Choice;
use App\Domain\MarketPlace\Repositories\Merchant\ChoiceRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class StoreChoiceAction
{

    public function __construct(protected ChoiceRepository $modelR)
    {
        $this->modelR = $modelR;
    }




    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->modelR->store($data);
    }

}

