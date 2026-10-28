<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Branch;

use App\Domain\MarketPlace\Repositories\Branch\BranchRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;

class DestroyBranchAction
{
    protected BranchRepository $branchRepository;


    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }


    public function handle(int $id): bool
    {
        return $this->branchRepository->destroy($id);
    }

}

