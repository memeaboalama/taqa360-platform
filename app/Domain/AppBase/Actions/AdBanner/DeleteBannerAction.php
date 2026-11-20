<?php

namespace App\Domain\AppBase\Actions\AdBanner;

use App\Domain\AppBase\Models\AdBanner;
use App\Domain\AppBase\Repositories\AdBannerRepository;

class DeleteBannerAction
{
    public function __construct(
        protected AdBannerRepository $bannerRepository
    ) {}

    public function handle(int $id): bool
    {
        $banner = $this->bannerRepository->findByCols(['id' => $id]);

        if (!$banner) {
            return false;
        }

        $result = $this->bannerRepository->destroy($id);
        if ($result) {
            AdBanner::forgetStorefrontCacheForPlacement((int) $banner->placement_id);
        }

        return $result;
    }
}
