<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\AdPlacement;
use App\Support\Enum\PlacementTypeEnum;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Database\Eloquent\Collection;

class AdPlacementRepository extends EloquentMainRepository
{
    public function __construct(AdPlacement $model)
    {
        parent::__construct($model);
    }

    public function getActiveForPage(int $pageId): Collection
    {
        return $this->modal
            ->forPage($pageId)
            ->active()
            ->with('activeBanners')
            ->ordered()
            ->get();
    }

    public function getWithBanners(int $id): ?AdPlacement
    {
        return $this->modal
            ->with(['banners' => function ($query) {
                $query->prioritized();
            }])
            ->find($id);
    }

    public function getByPageAndKey(string $pageKey, string $placementKey): ?AdPlacement
    {
        return $this->modal
            ->whereHas('page', function ($query) use ($pageKey) {
                $query->where('key', $pageKey);
            })
            ->where('key', $placementKey)
            ->with('activeBanners')
            ->first();
    }

    public function getByType(PlacementTypeEnum $type): Collection
    {
        return $this->modal
            ->ofType($type)
            ->active()
            ->get();
    }

    public function keyExistsForPage(int $pageId, string $key, ?int $excludeId = null): bool
    {
        $query = $this->modal
            ->where('page_id', $pageId)
            ->where('key', $key);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
