<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\Choice;

use App\Domain\MarketPlace\Repositories\Merchant\ChoiceRepository;

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
