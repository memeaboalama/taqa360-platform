<?php

namespace App\Application\Ecommerce\Controllers;

use App\Domain\AppBase\Models\AdPlacement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function filterTabs(Request $request, $placementId, $groupKey)
    {
        $placement = AdPlacement::with(['activeBanners' => function($query) {
            $query->prioritized();
        }])->findOrFail($placementId);

        $banners = $placement->activeBanners->filter(function($banner) use ($groupKey) {
            return ($banner->content['group'] ?? '') === $groupKey;
        });

        return view('ecommerce::partials.home-sections.product-grid', [
            'banners' => $banners,
            'placement' => $placement,
            'groupKey' => $groupKey
        ]);
    }
}
