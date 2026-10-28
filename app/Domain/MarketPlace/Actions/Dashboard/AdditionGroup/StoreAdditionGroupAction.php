<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\AdditionGroup;

use App\Domain\MarketPlace\Repositories\Merchant\AdditionGroupRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class StoreAdditionGroupAction
{

    public function __construct(protected AdditionGroupRepository $modelR)
    {
        $this->modelR = $modelR;
    }




    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $model = $this->modelR->store($data);

        foreach ($data['options'] as $option) {
            $model->choices()->attach($option['choice_id'], ['price' => $option['price'], 'order' => $option['order']]);
        }
        return $model;
    }
}
