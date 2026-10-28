<?php

namespace App\Domain\Admin\Actions\Dashboard\Package;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Domain\MarketPlace\Repositories\Merchant\PackageRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Collection;

class FetchSinglePackageAction
{
    protected PackageRepository $packageRepository;


    public function __construct(PackageRepository $packageRepository, MerchantRepository $merchantRepository)
    {
        $this->packageRepository = $packageRepository;
        $this->merchantRepository = $merchantRepository;
    }

    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->packageRepository->findByCols(['id' => $id]);
    }

    public function fetchByMerchant(int $merchant_id) : int|null
    {
        return $this->merchantRepository->findByCols(['id' => $merchant_id])->packages()->first()?->id;
    }
}
