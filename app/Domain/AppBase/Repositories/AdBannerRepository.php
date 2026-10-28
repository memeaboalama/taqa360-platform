<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\AdBanner;
use App\Support\Enum\BannerStatusEnum;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AdBannerRepository extends EloquentMainRepository
{
    public function __construct(AdBanner $model)
    {
        parent::__construct($model);
    }

    public function getDisplayableForPlacement(int $placementId, ?int $limit = null): Collection
    {
        $query = $this->modal
            ->forPlacement($placementId)
            ->active()
            ->withinSchedule()
            ->prioritized();

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public function getPaginatedForPlacement(int $placementId, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->modal->forPlacement($placementId);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereJsonContains('title->en', $search)
                    ->orWhereJsonContains('title->ar', $search);
            });
        }

        return $query->prioritized()->paginate($perPage);
    }

    public function getScheduledToBecomeActive(): Collection
    {
        return $this->modal
            ->where('status', BannerStatusEnum::SCHEDULED)
            ->where('start_date', '<=', now())
            ->whereNull('end_date')
            ->orWhere(function ($q) {
                $q->where('end_date', '>=', now());
            })
            ->get();
    }

    public function getActiveThatShouldExpire(): Collection
    {
        return $this->modal
            ->where('status', BannerStatusEnum::ACTIVE)
            ->whereNotNull('end_date')
            ->where('end_date', '<', now())
            ->get();
    }

    public function getTopPerformers(int $limit = 10): Collection
    {
        return $this->modal
            ->where('views_count', '>', 0)
            ->orderByRaw('(clicks_count / views_count) DESC')
            ->limit($limit)
            ->get();
    }

    public function incrementViews(int $bannerId): bool
    {
        return $this->modal->find($bannerId)?->incrementViews() ?? false;
    }

    public function incrementClicks(int $bannerId): bool
    {
        return $this->modal->find($bannerId)?->incrementClicks() ?? false;
    }

    public function updatePriorities(array $priorities): bool
    {
        foreach ($priorities as $id => $priority) {
            $this->modal->where('id', $id)->update(['priority' => $priority]);
        }

        return true;
    }
}
