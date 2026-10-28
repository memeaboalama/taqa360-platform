<?php

namespace App\Domain\MarketPlace\Actions\Api\Branch;

use App\Domain\MarketPlace\Repositories\Branch\BranchRepository;
use Domain\MarketPlace\Models\MerchantBranch;

class FetchSingleBranchAction
{
    protected BranchRepository $branchRepository;


    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }


    public function handle(int $id, int $merchant_id): \Illuminate\Database\Eloquent\Model|null
    {
        $lat = resolve('lat');
        $long = resolve('long');


        if ($lat && $long) {
            $location = [$long, $lat];
            return $this->branchRepository->findByCols(['id' => $id, 'merchant_id' => $merchant_id], ['merchant', 'typeServices','workDaysHour']);
        }


        return $this->branchRepository->findByCols(['id' => $id, 'merchant_id' => $merchant_id], ['merchant', 'typeServices','workDaysHour']);
        //  return $this->branchRepository->findByCols(['merchant_id' => $merchant_id, 'id' => $id]);
    }
}
