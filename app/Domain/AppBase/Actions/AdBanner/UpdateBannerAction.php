<?php

namespace App\Domain\AppBase\Actions\AdBanner;

use App\Domain\AppBase\Models\AdBanner;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Repositories\AdBannerRepository;
use App\Support\Services\ImagesServices\ImageService;
use App\Support\Enum\FileTypesEnum;
use App\Support\Enum\PlacementTypeEnum;

class UpdateBannerAction
{
    public function __construct(
        protected AdBannerRepository $bannerRepository,
        protected ImageService $imageService
    ) {}

    public function handle(int $id, array $data): bool
    {
        $banner = $this->bannerRepository->findByCols(['id' => $id]);

        if (!$banner) {
            return false;
        }

        if (isset($data['status'])) {
            $statusValue = $data['status'] instanceof \BackedEnum ? $data['status']->value : $data['status'];

            if ($statusValue === 'active' && $banner->status->value !== 'active') {
                $this->validatePlacementLimits($banner->placement_id, $data['status'], $id);
            }
        }

        if (isset($data['image_desktop']) || isset($data['image_tablet']) || isset($data['image_mobile']) || isset($data['content'])) {
            $content = $banner->content ?? [];

            if (isset($data['content']) && is_array($data['content'])) {
                $content = array_merge($content, $data['content']);
            }

            foreach (['desktop', 'tablet', 'mobile'] as $device) {
                $fileKey = "image_{$device}";
                if (isset($data[$fileKey]) && is_object($data[$fileKey])) {
                    $image = $this->imageService->storeImage(
                        $data[$fileKey],
                        'upload/ads/banners',
                        FileTypesEnum::BANNER
                    );
                    $content['images'][$device] = $image['path'];
                }
            }

            if (isset($data['image_favicon']) && is_object($data['image_favicon'])) {
                $image = $this->imageService->storeImage(
                    $data['image_favicon'],
                    'upload/ads/banners',
                    FileTypesEnum::BANNER
                );
                $content['images']['favicon'] = $image['path'];
            }

            $data['content'] = $content;
        }

        $result = $this->bannerRepository->update($id, $data);
        return $result ? true : false;
    }

    protected function validatePlacementLimits(int $placementId, string|\BackedEnum $status, int $currentBannerId = null): void
    {
        $status = $status instanceof \BackedEnum ? $status->value : $status;

        if ($status !== 'active') {
            return;
        }

        $placement = AdPlacement::find($placementId);

        if (!$placement) {
            return;
        }

        $placementType = $placement->type;

        if (!$placementType->isProductSection() && !$placementType->isCategorySection()) {
            return;
        }

        $maxItems = $placement->settings['max_items']
            ?? $placement->settings['slider_config']['max_items']
            ?? null;

        if ($maxItems === null) {
            return;
        }

        $query = AdBanner::where('placement_id', $placementId)
            ->where('status', 'active');

        if ($currentBannerId) {
            $query->where('id', '!=', $currentBannerId);
        }

        $activeCount = $query->count();

        if ($activeCount >= $maxItems) {
            throw new \Exception(__('admin::lang.Cannot activate. Maximum :max active items allowed for this section.', ['max' => $maxItems]));
        }
    }
}
