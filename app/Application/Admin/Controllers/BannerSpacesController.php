<?php

namespace Application\Admin\Controllers;

use App\Domain\AppBase\Models\AdPage;
use App\Domain\AppBase\Repositories\AdPageRepository;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Http\Controllers\Controller;
use Illuminate\View\View;


class BannerSpacesController extends Controller
{
    public function __construct(
        protected AdPageRepository $pageRepository,
        protected AdPlacementRepository $placementRepository
    ) {}

    public function index(): View
    {
        return view('admin::pages.banner-spaces.index');
    }

    public function show(int $pageId): View
    {
        $page = $this->pageRepository->findById($pageId);

        if (!$page) {
            abort(404, 'Page not found');
        }

        return view('admin::pages.banner-spaces.show', compact('page'));
    }
}
