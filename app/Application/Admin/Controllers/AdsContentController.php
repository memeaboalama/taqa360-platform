<?php

namespace Application\Admin\Controllers;

use App\Domain\AppBase\Models\AdPage;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Repositories\AdPageRepository;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Http\Controllers\Controller;
use Illuminate\View\View;


class AdsContentController extends Controller
{
    public function __construct(
        protected AdPageRepository $pageRepository,
        protected AdPlacementRepository $placementRepository
    ) {}

    public function index(): View
    {
        return view('admin::pages.ads-content.index');
    }

    public function manage(int $placementId): View
    {
        $placement = $this->placementRepository->getWithBanners($placementId);

        if (!$placement) {
            abort(404, 'Placement not found');
        }

        return view('admin::pages.ads-content.manage', compact('placement'));
    }
}
