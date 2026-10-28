<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\Choice;

use App\Domain\MarketPlace\Repositories\Merchant\ChoiceRepository;

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

