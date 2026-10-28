<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\Choice;

use App\Domain\MarketPlace\Repositories\Merchant\ChoiceRepository;

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
