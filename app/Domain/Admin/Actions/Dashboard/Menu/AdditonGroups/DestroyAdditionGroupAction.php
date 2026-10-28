<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\AdditonGroups;

use App\Domain\MarketPlace\Repositories\Merchant\AdditionGroupRepository;

class DestroyAdditionGroupAction
{
    public function __construct(protected AdditionGroupRepository $modelR)
    {
        $this->modelR = $modelR;
    }

    public function handle(int $id): bool
    {
        return $this->modelR->destroy($id);
    }
}
