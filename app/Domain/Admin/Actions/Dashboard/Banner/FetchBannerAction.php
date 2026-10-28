<?php

namespace App\Domain\Admin\Actions\Dashboard\Banner;

use App\Domain\AppBase\Repositories\BannerRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Collection;

class FetchBannerAction
{
    protected BannerRepository $bannerRepository;


    public function __construct(BannerRepository $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    public function handle(): Collection
    {
        return $this->bannerRepository->all(condition: ['status' => StatusEnum::ACTIVE->value]);
    }
}
