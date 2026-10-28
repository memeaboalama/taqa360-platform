<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\AdPage;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Database\Eloquent\Collection;

class AdPageRepository extends EloquentMainRepository
{
    public function __construct(AdPage $model)
    {
        parent::__construct($model);
    }

    public function getActiveWithActivePlacements(): Collection
    {
        return $this->modal
            ->active()
            ->with(['activePlacements' => function ($query) {
                $query->with('activeBanners');
            }])
            ->ordered()
            ->get();
    }

    public function getByKey(string $key): ?AdPage
    {
        return $this->modal->where('key', $key)->first();
    }

    public function getByKeyWithPlacements(string $key): ?AdPage
    {
        return $this->modal
            ->where('key', $key)
            ->with(['activePlacements' => function ($query) {
                $query->with('activeBanners');
            }])
            ->first();
    }
}
