<?php

namespace App\Domain\MarketPlace\Actions\Api\Merchant;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use Domain\MarketPlace\Models\Merchant;
use Domain\MarketPlace\Models\MerchantBranch;

class FetchSingleMerchantAction
{
    protected MerchantRepository $merchantRepository;


    public function __construct(MerchantRepository $merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;
    }


    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        $lat = resolve('lat');
        $long = resolve('long');


        if ($lat && $long) {
            $location = [$long, $lat];
            return $this->merchantRepository->findByCols(['id' => $id], ['branches', 'branches.typeServices','branches.workDaysHour']);
        }


        return $this->merchantRepository->findByCols(['id' => $id], ['branches', 'branches.typeServices','branches.workDaysHour']);
    }
}
