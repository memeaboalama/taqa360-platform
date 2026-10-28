<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\AdProductContent;
use App\Support\Repositories\Main\EloquentMainRepository;

class AdProductContentRepository extends EloquentMainRepository
{
    public function __construct(AdProductContent $model)
    {
        parent::__construct($model);
    }

    public function getForPlacement(int $placementId, ?string $groupKey = null, bool $activeOnly = true)
    {
        $query = $this->modal->forPlacement($placementId);

        if ($activeOnly) {
            $query->active()->withinSchedule();
        }

        if ($groupKey !== null) {
            $query->forGroup($groupKey);
        }

        return $query->prioritized()->with('product')->get();
    }

    public function getActiveForPlacement(int $placementId)
    {
        return $this->modal
            ->forPlacement($placementId)
            ->active()
            ->withinSchedule()
            ->prioritized()
            ->with('product')
            ->get();
    }

    public function getGroupedForPlacement(int $placementId)
    {
        return $this->modal
            ->forPlacement($placementId)
            ->active()
            ->withinSchedule()
            ->with('product')
            ->get()
            ->groupBy('group_key');
    }

    public function updatePriorities(array $items): void
    {
        foreach ($items as $item) {
            $this->modal->where('id', $item['id'])
                ->update(['priority' => $item['priority']]);
        }
    }

    public function deleteExpired(): int
    {
        return $this->modal
            ->where('end_date', '<', now())
            ->where('status', '!=', 'expired')
            ->update(['status' => 'expired']);
    }
}
