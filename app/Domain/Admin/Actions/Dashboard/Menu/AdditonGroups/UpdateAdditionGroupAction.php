<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\AdditonGroups;

use App\Domain\MarketPlace\Repositories\Merchant\AdditionGroupRepository;

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
