<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Branch;

use App\Domain\MarketPlace\Repositories\Branch\BranchRepository;

class FetchSingleBranchAction
{
    protected BranchRepository $branchRepository;


    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }


    public function handle($id, int $merchant_id = null): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->branchRepository->findByCols(['merchant_id' => $merchant_id, 'id' => $id]);
    }

}

