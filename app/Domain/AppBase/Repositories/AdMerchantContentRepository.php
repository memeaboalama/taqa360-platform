<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\AdMerchantContent;
use App\Support\Repositories\Main\EloquentMainRepository;

class AdMerchantContentRepository extends EloquentMainRepository
{
    public function __construct(AdMerchantContent $model)
    {
        parent::__construct($model);
    }

    public function getActiveMerchantsForPlacement(int $placementId, ?string $groupKey = null, ?int $limit = null)
    {
        $query = $this->builder()
            ->where('placement_id', $placementId)
            ->active()
            ->withinSchedule()
            ->with('merchant')
            ->prioritized();

        if ($groupKey !== null) {
            $query->forGroup($groupKey);
        }

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public function getMerchantsByPlacement(int $placementId, ?string $status = null)
    {
        $query = $this->builder()
            ->where('placement_id', $placementId)
            ->with('merchant')
            ->prioritized();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    public function storeMultiple(int $placementId, array $merchantIds, array $commonData = []): array
    {
        $created = [];

        foreach ($merchantIds as $index => $merchantId) {
            $data = array_merge([
                'placement_id' => $placementId,
                'merchant_id' => $merchantId,
                'priority' => $commonData['priority'] ?? ($index + 1),
                'status' => $commonData['status'] ?? 'active',
                'start_date' => $commonData['start_date'] ?? null,
                'end_date' => $commonData['end_date'] ?? null,
                'group_key' => $commonData['group_key'] ?? null,
                'created_by' => $commonData['created_by'] ?? auth()->id(),
            ]);

            $created[] = $this->store($data);
        }

        return $created;
    }
}
