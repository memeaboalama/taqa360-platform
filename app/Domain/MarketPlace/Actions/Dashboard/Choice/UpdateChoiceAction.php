<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Choice;

use App\Domain\MarketPlace\Repositories\Merchant\ChoiceRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class UpdateChoiceAction
{


    public function __construct(protected ChoiceRepository $modelR)
    {
        $this->modelR = $modelR;
    }



    public function handle(int $category_id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->modelR->update($category_id, $data);
    }

}

