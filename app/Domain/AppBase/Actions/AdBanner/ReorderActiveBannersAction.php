<?php

namespace App\Domain\AppBase\Actions\AdBanner;

use App\Domain\AppBase\Models\AdBanner;
use Illuminate\Support\Facades\DB;

class ReorderActiveBannersAction
{
    public function handle($placementId)
    {
        $banners = AdBanner::where('placement_id', $placementId)
            ->where('status', 'active')
            ->orderBy('priority')
            ->orderBy('updated_at', 'desc')
            ->get();

        DB::transaction(function () use ($banners) {
            foreach ($banners as $index => $banner) {
                $banner->update(['priority' => $index + 1]);
            }
        });
    }
}
