<?php

namespace Application\Admin\Controllers;

use App\Domain\AppBase\Repositories\AdPageRepository;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdPlacementController extends Controller
{
    public function __construct(
        protected AdPageRepository $pageRepository,
        protected AdPlacementRepository $placementRepository
    ) {}

    public function index(int $pageId): View
    {
        $page = $this->pageRepository->findById($pageId);

        return view('admin::pages.ad-management.placements.index', compact('page'));
    }

    public function show(int $placementId): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('admin:ad-management.banners.index', $placementId);
    }
}
