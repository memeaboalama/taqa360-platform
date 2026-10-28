<?php

namespace App\Domain\Admin\Actions\Dashboard\Banner;

use App\Domain\AppBase\Repositories\BannerRepository;

class FetchSingleBannerAction
{
    protected BannerRepository $bannerRepository;


    public function __construct(BannerRepository $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }


    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->bannerRepository->findByCols(['id' => $id]);
    }
}
