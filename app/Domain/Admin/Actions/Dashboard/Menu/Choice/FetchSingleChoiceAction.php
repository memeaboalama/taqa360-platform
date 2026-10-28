<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\Choice;

use App\Domain\MarketPlace\Repositories\Merchant\ChoiceRepository;

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
