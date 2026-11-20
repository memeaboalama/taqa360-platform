<?php

namespace App\Domain\AppBase\Actions\AdBanner;

use App\Domain\AppBase\Models\AdBanner;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Repositories\AdBannerRepository;
use App\Support\Services\ImagesServices\ImageService;
use App\Support\Enum\FileTypesEnum;
use App\Support\Enum\PlacementTypeEnum;
use Illuminate\Http\UploadedFile;

class StoreBannerAction
{
    public function __construct(
        protected AdBannerRepository $bannerRepository,
        protected ImageService $imageService
    ) {}

    public function handle(array $data): AdBanner
    {
        if (isset($data['placement_id']) && isset($data['status'])) {
            $this->validatePlacementLimits($data['placement_id'], $data['status']);
        }

        $data = $this->formatDataForPlacement($data);

        $processedContent = $this->processContent($data);
        $existingContent = $data['content'] ?? [];
        $data['content'] = array_merge($existingContent, $processedContent);

        if (!isset($data['created_by'])) {
            $data['created_by'] = auth()->id();
        }

        $banner = $this->bannerRepository->store($data);
        AdBanner::forgetStorefrontCacheForPlacement((int) $data['placement_id']);

        return $banner;
    }

    protected function formatDataForPlacement(array $data): array
    {
        if (!isset($data['placement_id'])) {
            return $data;
        }

        $placement = AdPlacement::find($data['placement_id']);

        if (!$placement) {
            return $data;
        }

        $placementType = $placement->type;

        if ($placementType->isProductSection()) {
            return $this->formatProductContent($data, $placement);
        }

        if ($placementType->isCategorySection()) {
            return $this->formatCategoryContent($data, $placement);
        }

        return $data;
    }

    protected function formatProductContent(array $data, $placement): array
    {
        if (isset($data['product_id']) && !isset($data['click_action'])) {
            $data['click_action'] = [
                'type' => 'product',
                'id' => $data['product_id']
            ];
        }

        if ($placement->type->value === 'product_tabs' && isset($data['group'])) {
            $data['content'] = array_merge($data['content'] ?? [], ['group' => $data['group']]);
        }

        if (!isset($data['priority'])) {
            $data['priority'] = 0;
        }

        unset($data['product_id'], $data['group']);

        return $data;
    }

    protected function formatCategoryContent(array $data, $placement): array
    {
        if (isset($data['category_id']) && !isset($data['click_action'])) {
            $data['click_action'] = [
                'type' => 'category',
                'id' => $data['category_id']
            ];
        }

        if (!isset($data['priority'])) {
            $data['priority'] = 0;
        }

        unset($data['category_id']);

        return $data;
    }

    protected function validatePlacementLimits(int $placementId, string|\BackedEnum $status): void
    {
        $status = $status instanceof \BackedEnum ? $status->value : $status;

        if ($status !== 'active') {
            return;
        }

        $placement = AdPlacement::find($placementId);

        if (!$placement) {
            return;
        }

        $placementType = PlacementTypeEnum::from($placement->type->value);

        if (!$placementType->isProductSection() && !$placementType->isCategorySection()) {
            return;
        }

        $maxItems = $placement->settings['max_items']
            ?? $placement->settings['slider_config']['max_items']
            ?? null;

        if ($maxItems === null) {
            return;
        }

        $activeCount = AdBanner::where('placement_id', $placementId)
            ->where('status', 'active')
            ->count();

        if ($activeCount >= $maxItems) {
            throw new \Exception(__('admin::lang.Cannot add more items. Maximum :max items allowed for this section.', ['max' => $maxItems]));
        }
    }

    protected function processContent(array $data): array
    {
        $content = [];

        if (isset($data['image_desktop']) && is_object($data['image_desktop'])) {
            $image = $this->storeBannerImage($data['image_desktop'], 'desktop');
            $content['images']['desktop'] = $image['path'];
        }

        if (isset($data['image_tablet']) && is_object($data['image_tablet'])) {
            $image = $this->storeBannerImage($data['image_tablet'], 'tablet');
            $content['images']['tablet'] = $image['path'];
        }

        if (isset($data['image_mobile']) && is_object($data['image_mobile'])) {
            $image = $this->storeBannerImage($data['image_mobile'], 'mobile');
            $content['images']['mobile'] = $image['path'];
        }

        if (isset($data['image_favicon']) && is_object($data['image_favicon'])) {
            $image = $this->storeBannerImage($data['image_favicon'], 'favicon');
            $content['images']['favicon'] = $image['path'];
        }

        if (isset($data['video_file']) && is_object($data['video_file'])) {
            $video = $this->imageService->storeImage(
                $data['video_file'],
                'upload/ads/videos',
                FileTypesEnum::VIDEO
            );
            $content['video']['file'] = $video['path'];
        }

        if (isset($data['video_poster']) && is_object($data['video_poster'])) {
            $image = $this->imageService->storeImage(
                $data['video_poster'],
                'upload/ads/videos',
                FileTypesEnum::BANNER
            );
            $content['video']['poster'] = $image['path'];
        }

        if (isset($data['html_content'])) {
            $content['html'] = $data['html_content'];
        }

        if (isset($data['alt_text'])) {
            $content['alt_text'] = $data['alt_text'];
        }

        return $content;
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

}
