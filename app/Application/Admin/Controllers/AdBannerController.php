<?php

namespace Application\Admin\Controllers;

use App\Domain\AppBase\Repositories\AdBannerRepository;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdBannerController extends Controller
{
    public function __construct(
        protected AdPlacementRepository $placementRepository,
        protected AdBannerRepository $bannerRepository
    ) {}

    public function index(int $placementId): View
    {
        $placement = $this->placementRepository->getWithBanners($placementId);

        return view('admin::pages.ad-management.banners.index', compact('placement'));
    }

    public function trackView(int $bannerId)
    {
        $this->bannerRepository->incrementViews($bannerId);

        return response()->json(['success' => true]);
    }

    public function trackClick(int $bannerId)
    {
        $this->bannerRepository->incrementClicks($bannerId);

        return response()->json(['success' => true]);
    }
}
