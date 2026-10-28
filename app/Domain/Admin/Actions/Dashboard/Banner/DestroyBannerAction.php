<?php

namespace App\Domain\Admin\Actions\Dashboard\Banner;

use App\Domain\AppBase\Repositories\BannerRepository;

class DestroyBannerAction
{
    protected BannerRepository $bannerRepository;


    public function __construct(BannerRepository $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }


    public function handle(int $id): bool
    {
        return $this->bannerRepository->destroy($id);
    }
}
