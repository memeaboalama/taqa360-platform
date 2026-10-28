<?php

namespace Application\Admin\Livewire\AdPlacement;

use App\Domain\AppBase\Actions\AdPlacement\DuplicatePlacementAction;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Support\Enum\PlacementTypeEnum;
use Livewire\Component;
use Livewire\Attributes\On;

class SpaceCards extends Component
{
    public int $pageId;
    public string $activeTab = 'all';

    public function mount(int $pageId, string $tab = 'all')
    {
        $this->pageId = $pageId;
        $this->activeTab = $tab;
    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function duplicatePlacement($placementId)
    {
        try {
            $duplicateAction = new DuplicatePlacementAction(
                new AdPlacementRepository(new AdPlacement())
            );

            $duplicate = $duplicateAction->handle($placementId);

            $this->dispatch('placement-created');

            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => __('admin::lang.Placement duplicated successfully')
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => __('admin::lang.Error duplicating placement') . ': ' . $e->getMessage()
            ]);
        }
    }

    #[On('placement-created')]
    #[On('placement-updated')]
    #[On('placement-deleted')]
    public function render()
    {
        $query = AdPlacement::where('page_id', $this->pageId)
            ->withCount('banners');

        // Filter by tab
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
                            PlacementTypeEnum::CAROUSEL->value, // Categories
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

        $placements = $query->get();

        return view('admin::livewire.ad-placement.space-cards', [
            'placements' => $placements
        ]);
    }
}
