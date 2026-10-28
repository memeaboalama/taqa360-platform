<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\AdditionGroup;

use App\Domain\MarketPlace\Repositories\Merchant\AdditionGroupRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class UpdateAdditionGroupAction
{
    public function __construct(protected AdditionGroupRepository $modelR)
    {
        $this->modelR = $modelR;
    }



    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $model = $this->modelR->findByCols(['id' => $id]);
        $model->choices()->detach();
        foreach ($data['options'] as $option) {
            $model->choices()->attach($option['choice_id'], ['price' => $option['price'], 'order' => $option['order']]);
        }
        $model->update($data);
        return $model;
    }
}
