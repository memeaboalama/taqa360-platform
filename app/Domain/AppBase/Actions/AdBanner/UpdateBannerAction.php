<?php

namespace App\Domain\AppBase\Actions\AdBanner;

use App\Domain\AppBase\Models\AdBanner;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Repositories\AdBannerRepository;
use App\Support\Services\ImagesServices\ImageService;
use App\Support\Enum\FileTypesEnum;
use App\Support\Enum\PlacementTypeEnum;
use Illuminate\Http\UploadedFile;

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
                    $image = $this->storeBannerImage($data[$fileKey], $device);
                    $content['images'][$device] = $image['path'];
                }
            }

            if (isset($data['image_favicon']) && is_object($data['image_favicon'])) {
                $image = $this->storeBannerImage($data['image_favicon'], 'favicon');
                $content['images']['favicon'] = $image['path'];
            }

            $data['content'] = $content;
        }

        $result = $this->bannerRepository->update($id, $data);
        if (!$result) {
            throw new \RuntimeException('Unable to save banner changes.');
        }

        AdBanner::forgetStorefrontCacheForPlacement((int) $banner->placement_id);

        return true;
    }

    private function storeBannerImage(UploadedFile $file, string $device): array
    {
        try {
            return $this->imageService->storeImage(
                $file,
                'upload/ads/banners',
                FileTypesEnum::BANNER
            );
        } catch (\Throwable $exception) {
            $field = __('admin::lang.' . match ($device) {
                'desktop' => 'Desktop Image',
                'tablet' => 'Tablet Image',
                'mobile' => 'Mobile Image',
                default => 'Favicon',
            });

            throw new \RuntimeException(
                __('admin::lang.Unable to save :field: :reason', [
                    'field' => $field,
                    'reason' => $exception->getMessage(),
                ]),
                0,
                $exception
            );
        }
    }

    protected function validatePlacementLimits(int $placementId, string|\BackedEnum $status, ?int $currentBannerId = null): void
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
