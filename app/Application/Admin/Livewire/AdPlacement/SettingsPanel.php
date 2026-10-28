<?php

namespace Application\Admin\Livewire\AdPlacement;

use App\Domain\AppBase\Models\AdPage;
use App\Domain\AppBase\Models\AdPlacement;
use Livewire\Component;
use Livewire\Attributes\On;

class SettingsPanel extends Component
{
    public $selectedPageId;
    public $pages;
    public $placements;
    public $expandedPlacementId = null;

    public function mount($pageId = null)
    {
        $this->pages = AdPage::withCount('placements')->orderBy('sort_order')->get();

        if ($pageId) {
            $this->selectedPageId = (int) $pageId;
        } else {
            $this->selectedPageId = $this->pages->first()?->id;
        }

        $this->loadPlacements();
    }

    public function selectPage(int $pageId)
    {
        $this->selectedPageId = $pageId;
        $this->expandedPlacementId = null;
        $this->loadPlacements();
    }

    public function togglePlacement(int $placementId)
    {
        if ((int) $this->expandedPlacementId === $placementId) {
            $this->expandedPlacementId = null;
        } else {
            $this->expandedPlacementId = $placementId;
        }
    }

    protected function loadPlacements()
    {
        $this->placements = AdPlacement::where('page_id', $this->selectedPageId)
            ->withCount('banners')
            ->orderBy('sort_order')
            ->get();
    }

    #[On('placement-created')]
    #[On('placement-updated')]
    #[On('placement-deleted')]
    public function refresh()
    {
        $this->loadPlacements();
        $this->pages = AdPage::withCount('placements')->orderBy('sort_order')->get();
    }

    public function render()
    {
        $selectedPage = $this->pages->firstWhere('id', $this->selectedPageId);

        return view('admin::livewire.ad-placement.settings-panel', [
            'selectedPage' => $selectedPage,
        ]);
    }
}
