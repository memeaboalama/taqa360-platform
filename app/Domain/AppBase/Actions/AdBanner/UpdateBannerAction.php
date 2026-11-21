<?php

namespace App\Domain\AppBase\Actions\AdBanner;

use App\Domain\AppBase\Models\AdBanner;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Repositories\AdBannerRepository;
use App\Support\Services\ImagesServices\ImageService;
use App\Support\Enum\FileTypesEnum;
use App\Support\Enum\PlacementTypeEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

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
            throw new \RuntimeException('Ad banner not found.');
        }

        $storedImagePaths = [];

        try {
            DB::transaction(function () use ($id, $data, $banner, &$storedImagePaths): void {
                if (isset($data['status'])) {
                    $statusValue = $data['status'] instanceof \BackedEnum ? $data['status']->value : $data['status'];

                    if ($statusValue === 'active' && $banner->status->value !== 'active') {
                        $this->validatePlacementLimits($banner->placement_id, $data['status'], $id);
                    }
                }

                $oldImagePaths = $this->getImagePaths($banner);

                if (
                    isset($data['image_desktop'])
                    || isset($data['image_tablet'])
                    || isset($data['image_mobile'])
                    || isset($data['image_favicon'])
                    || isset($data['content'])
                ) {
                    $content = $banner->content ?? [];

                    if (isset($data['content']) && is_array($data['content'])) {
                        $content = array_merge($content, $data['content']);
                    }

                    foreach (['desktop', 'tablet', 'mobile'] as $device) {
                        $fileKey = "image_{$device}";
                        if (isset($data[$fileKey]) && $data[$fileKey] instanceof UploadedFile) {
                            $image = $this->storeBannerImage($data[$fileKey], $device);
                            $storedImagePaths[] = $image['path'];
                            $content['images'][$device] = $image['path'];
                        }
                    }

                    if (isset($data['image_favicon']) && $data['image_favicon'] instanceof UploadedFile) {
                        $image = $this->storeBannerImage($data['image_favicon'], 'favicon');
                        $storedImagePaths[] = $image['path'];
                        $content['images']['favicon'] = $image['path'];
                    }

                    $data['content'] = $content;
                }

                $banner->fill($data);
                if (!$banner->save()) {
                    throw new \RuntimeException('Unable to save banner changes.');
                }

                $removedImagePaths = array_values(array_diff($oldImagePaths, $this->getImagePaths($banner)));
                $placementId = (int) $banner->placement_id;

                DB::afterCommit(function () use ($placementId, $removedImagePaths): void {
                    try {
                        AdBanner::forgetStorefrontCacheForPlacement($placementId);
                    } catch (Throwable $exception) {
                        Log::error('Unable to clear ad banner storefront cache.', [
                            'placement_id' => $placementId,
                            'exception' => $exception,
                        ]);
                    }

                    $this->deleteUnusedImages($removedImagePaths);
                });
            });
        } catch (Throwable $exception) {
            $this->deleteUnusedImages($storedImagePaths);
            throw $exception;
        }

        return true;
    }

    private function getImagePaths(AdBanner $banner): array
    {
        $content = $banner->content ?? [];
        $images = is_array($content['images'] ?? null) ? $content['images'] : [];
        $paths = array_merge(
            array_values($images),
            [$content['image'] ?? null, $content['image_desktop'] ?? null]
        );

        return array_values(array_unique(array_filter(
            $paths,
            static fn ($path): bool => is_string($path) && $path !== ''
        )));
    }

    private function deleteUnusedImages(array $paths): void
    {
        foreach (array_unique($paths) as $path) {
            if (!is_string($path) || !$this->isManagedImagePath($path)) {
                continue;
            }

            try {
                if (!$this->isImageReferenced($path)) {
                    $this->imageService->destroyImg(ltrim($path, '/'));
                }
            } catch (Throwable $exception) {
                Log::warning('Unable to clean up an ad banner image.', [
                    'path' => $path,
                    'exception' => $exception,
                ]);
            }
        }
    }

    private function isImageReferenced(string $path): bool
    {
        $normalizedPath = ltrim($path, '/');

        return AdBanner::withTrashed()
            ->where(function ($query) use ($path, $normalizedPath): void {
                foreach (['desktop', 'tablet', 'mobile', 'favicon'] as $device) {
                    $query->orWhere("content->images->{$device}", $path)
                        ->orWhere("content->images->{$device}", $normalizedPath);
                }

                $query->orWhere('content->image', $path)
                    ->orWhere('content->image', $normalizedPath)
                    ->orWhere('content->image_desktop', $path)
                    ->orWhere('content->image_desktop', $normalizedPath);
            })
            ->exists();
    }

    private function isManagedImagePath(string $path): bool
    {
        return str_starts_with(ltrim($path, '/'), 'upload/ads/banners/');
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
