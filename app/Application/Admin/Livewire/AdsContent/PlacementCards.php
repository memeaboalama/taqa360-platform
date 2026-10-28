<?php

namespace Application\Admin\Livewire\AdsContent;

use App\Domain\AppBase\Models\AdPage;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Support\Enum\PlacementTypeEnum;
use Livewire\Component;

class PlacementCards extends Component
{
    public $pageId;
    public $placements = [];
    public $viewMode = 'grid';
    public string $activeTab = 'all';
    public ?int $loadingPlacementId = null;

    protected $listeners = ['contentPageChanged'];

    public function toggleViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
        $this->loadPlacements();
    }

    public function mount($pageId = null)
    {
        if (!$pageId) {
            $firstPage = AdPage::orderBy('sort_order')->first();
            $pageId = $firstPage?->id;
        }

        $this->pageId = $pageId;
        $this->loadPlacements();
    }

    public function contentPageChanged($pageId)
    {
        $this->pageId = $pageId;
        $this->loadPlacements();
    }

    public function loadPlacements()
    {
        if (!$this->pageId) {
            $this->placements = [];
            return;
        }

        $repository = new AdPlacementRepository(new AdPlacement());
        $query = $repository->builder()
            ->where('page_id', $this->pageId)
            ->where('status', 'active')
            ->withCount(['banners', 'productContents', 'merchantContents', 'categoryContents'])
            ->orderBy('sort_order');

        if ($this->activeTab !== 'all') {
            $query->where(function($q) {
                switch($this->activeTab) {
                    case 'banners':
                        $q->whereIn('type', [
                            PlacementTypeEnum::SLIDER->value,
                            PlacementTypeEnum::STATIC_IMAGE->value,
                            PlacementTypeEnum::GRID_BANNERS->value,
                            PlacementTypeEnum::VIDEO_BANNER->value,
                        ]);
                        break;
                    case 'products':
                        $q->whereIn('type', [
                            PlacementTypeEnum::PRODUCT_TABS->value,
                            PlacementTypeEnum::PRODUCT_SLIDER->value,
                            PlacementTypeEnum::PRODUCT_SIDEBAR->value,
                            PlacementTypeEnum::CAROUSEL->value,
                        ]);
                        break;
                    case 'brands':
                        $q->whereIn('type', [
                            PlacementTypeEnum::MERCHANT_SLIDER->value,
                        ]);
                        break;
                }
            });
        }

        $this->placements = $query->get();
    }

    public function goToPlacement($placementId)
    {
        if ($this->loadingPlacementId !== null) {
            return null;
        }

        $this->loadingPlacementId = (int) $placementId;

        return redirect()->route('admin:ads-content.manage', $placementId);
    }

    public function render()
    {
        return view('admin::livewire.ads-content.placement-cards');
    }
}
