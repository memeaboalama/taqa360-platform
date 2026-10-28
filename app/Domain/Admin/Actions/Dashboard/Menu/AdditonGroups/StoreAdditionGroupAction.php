<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\AdditonGroups;

use App\Domain\MarketPlace\Repositories\Merchant\AdditionGroupRepository;

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
