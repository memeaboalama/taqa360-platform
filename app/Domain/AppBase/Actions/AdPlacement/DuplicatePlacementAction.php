<?php

namespace App\Domain\AppBase\Actions\AdPlacement;

use App\Domain\AppBase\Models\AdBanner;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use Illuminate\Support\Str;

class DuplicatePlacementAction
{
    public function __construct(
        protected AdPlacementRepository $placementRepository
    ) {}

    public function handle(int $placementId): AdPlacement
    {
        $original = $this->placementRepository->findByCols(['id' => $placementId]);

        $duplicateData = $original->toArray();

        unset($duplicateData['id'], $duplicateData['created_at'], $duplicateData['updated_at']);

        $duplicateData['name'] = $this->generateUniqueName($original->name);

        if (!empty($duplicateData['key'])) {
            $duplicateData['key'] = $this->generateUniqueKey($duplicateData['key']);
        }

        $duplicate = $this->placementRepository->store($duplicateData);

        foreach ($original->banners as $banner) {
            $bannerData = $banner->toArray();
            unset($bannerData['id'], $bannerData['created_at'], $bannerData['updated_at']);
            $bannerData['placement_id'] = $duplicate->id;
            $bannerData['name'] = $banner->name . ' (Copy)';

            AdBanner::create($bannerData);
        }

        return $duplicate;
    }

    protected function generateUniqueName(array|string $originalName): array|string
    {
        if (is_array($originalName)) {
            return array_map(fn($name) => $name . ' (Copy)', $originalName);
        }

        return $originalName . ' (Copy)';
    }

    protected function generateUniqueKey(string $originalKey): string
    {
        $baseKey = $originalKey;
        $counter = 1;
        $newKey = $baseKey . '_ copy';

        while ($this->placementRepository->builder()->where('key', $newKey)->exists()) {
            $counter++;
            $newKey = $baseKey . '_copy_' . $counter;
        }

        return $newKey;
    }
}
