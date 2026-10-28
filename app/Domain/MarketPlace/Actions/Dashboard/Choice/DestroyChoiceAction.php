<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Choice;

use App\Domain\MarketPlace\Repositories\Merchant\ChoiceRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantChoiceRepository;

class DestroyChoiceAction
{


    public function __construct(protected ChoiceRepository $modelR)
    {
        $this->modelR = $modelR;
    }


    public function handle(int $id): bool
    {
        return $this->modelR->destroy($id);
    }
}
