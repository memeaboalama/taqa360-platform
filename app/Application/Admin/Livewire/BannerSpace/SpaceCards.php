<?php

namespace Application\Admin\Livewire\BannerSpace;

use App\Domain\AppBase\Models\AdPage;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Support\Enum\PlacementTypeEnum;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class SpaceCards extends Component
{
    use LivewireAlert;

    public $pageId;
    public $placements = [];
    public $viewMode = 'grid';
    public string $activeTab = 'all';

    protected $listeners = ['pageChanged', 'refreshSpaces'];

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

    public function pageChanged($pageId)
    {
        $this->pageId = $pageId;
        $this->loadPlacements();
    }

    public function refreshSpaces()
    {
        $this->loadPlacements();
    }

    public function loadPlacements()
    {
        if (!$this->pageId) {
            $this->placements = [];
            return;
        }

        $repository = new AdPlacementRepository(new \App\Domain\AppBase\Models\AdPlacement());
        $query = $repository->builder()
            ->where('page_id', $this->pageId)
            ->withCount('banners')
            ->reorder()
            ->orderByDesc('created_at');

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

    public function openCreateModal()
    {
        $this->emit('openCreateSpaceModal', $this->pageId);
    }

    public function copyPlacement($placementId)
    {
        $this->emit('openCreateSpaceModalFromCopy', $placementId);
    }

    public function openUpdateModal($placementId)
    {
        $this->emit('openUpdateSpaceModal', $placementId);
    }

    public function openDeleteModal($placementId)
    {
        $this->emit('openDeleteSpaceModal', $placementId);
    }

    public function render()
    {
        return view('admin::livewire.banner-space.space-cards');
    }
}
