<?php

namespace App\Domain\Admin\Actions\Dashboard\Package;

use App\Domain\MarketPlace\Repositories\Merchant\PackageRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Collection;

class FetchPackageAction
{
    protected PackageRepository $packageRepository;


    public function __construct(PackageRepository $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    public function handle(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->packageRepository->all();
    }

    public function active(): Collection
    {
        return $this->packageRepository->all(condition: ['status' => StatusEnum::ACTIVE->value]);
    }
}
