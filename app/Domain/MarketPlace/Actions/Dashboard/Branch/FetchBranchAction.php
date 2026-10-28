<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Branch;

use App\Domain\MarketPlace\Repositories\Branch\BranchRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchBranchAction
{
    protected BranchRepository $branchRepository;


    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }


    public function handle(int $merchant_id = null): Collection
    {
        return $this->branchRepository->all(cols:['id', 'merchant_id', 'title'],condition:["merchant_id"=> $merchant_id]);
    }

}

