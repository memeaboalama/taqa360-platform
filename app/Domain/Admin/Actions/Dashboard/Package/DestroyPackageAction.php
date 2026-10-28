<?php

namespace App\Domain\Admin\Actions\Dashboard\Package;

use App\Domain\MarketPlace\Repositories\Merchant\PackageRepository;
use App\Support\Enum\StatusEnum;

class DestroyPackageAction
{
    protected PackageRepository $packageRepository;


    public function __construct(PackageRepository $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }


    public function handle(int $id): bool
    {
        return $this->packageRepository->findByCols(['id' =>$id])->update(['status' => StatusEnum::INACTIVE->value]);
    }
}
