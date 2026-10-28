<?php

namespace App\Domain\AppBase\Actions\Home;

use App\Domain\AppBase\Repositories\AdBannerRepository;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use Illuminate\Support\Facades\Cache;

class FetchBannersAction
{
    public function __construct(
        private AdPlacementRepository $adPlacementRepository,
        private AdBannerRepository $adBannerRepository
    ) {}

    public function handle(string $placementKey)
    {
        return Cache::remember("ecommerce_banners_{$placementKey}_" . app()->getLocale(), 1800, function() use ($placementKey) {
            $placement = $this->adPlacementRepository->getByPageAndKey('ecommerce_home', $placementKey);

            if (!$placement) {
                return collect();
            }

            $limit = $placement->settings['slider_config']['max_items'] 
                ?? $placement->settings['max_items'] 
                ?? null;

            return $this->adBannerRepository->builder(order:'asc', orderCol: 'priority')
                ->where('placement_id', $placement->id)
                ->where('status', 'active')
                ->where(function($q) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->when($limit, fn($q) => $q->limit($limit))
                ->get();
        });
    }
}
