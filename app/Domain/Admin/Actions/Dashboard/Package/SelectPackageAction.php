<?php

namespace App\Domain\Admin\Actions\Dashboard\Package;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Domain\MarketPlace\Repositories\Merchant\PackageRepository;

class SelectPackageAction
{
    protected PackageRepository $packageRepository;
    protected MerchantRepository $merchantRepository;


    public function __construct(PackageRepository $packageRepository, MerchantRepository $merchantRepository)
    {
        $this->packageRepository = $packageRepository;
        $this->merchantRepository = $merchantRepository;
    }

    public function handle(int $id,int $merchant_id): \Illuminate\Database\Eloquent\Model|null
    {
        $merchant = $this->merchantRepository->findByCols(['id' => $merchant_id]);
        $merchant->packages()->detach();
        return $merchant->packages()->attach($id);
    }
}
