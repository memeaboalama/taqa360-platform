<?php

namespace Application\Admin\Livewire\AdsContent\Modal;

use App\Domain\AppBase\Repositories\AdBannerRepository;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Domain\AppBase\Actions\AdBanner\ReorderActiveBannersAction;
use App\Domain\AppBase\Actions\AdProductContent\ReorderProductContentsAction;
use App\Domain\AppBase\Models\AdBanner;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Models\AdProductContent;
use App\Domain\AppBase\Repositories\AdProductContentRepository;
use App\Support\Enum\PlacementTypeEnum;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Reactivate extends Component
{
    use LivewireAlert;

    public $contentType = 'banner';
    public $bannerId;
    public $placementId;
    public $showReplacementModal = false;
    public $activeBanners = [];
    public $selectedBannerToReplace;
    public $maxItems;
    public $currentCount;
    public $start_date;
    public $end_date;
    public $isLimitReached = false;

    protected $listeners = ['openReactivateModal'];

    public function openReactivateModal($contentId, $placementId, $type = 'banner')
    {
        $this->contentType = $type;
        $this->attemptReactivation($contentId, $placementId);
    }

    public function attemptReactivation($contentId, $placementId)
    {
        $this->bannerId = $contentId;
        $this->placementId = $placementId;

        $placementRepo = new AdPlacementRepository(new AdPlacement());

        if ($this->contentType === 'product') {
            $repo = new AdProductContentRepository(new AdProductContent());
        } else {
            $repo = new AdBannerRepository(new AdBanner());
        }

        $placement = $placementRepo->findById($placementId);

        $this->maxItems = $placement->settings['slider_config']['max_items'] ?? $placement->settings['max_items'] ?? null;

        $this->currentCount = $repo->builder()
            ->where('placement_id', $placementId)
            ->where('status', 'active')
            ->count();

        $type = $placement->type;
        $isProductTabs = ($type instanceof PlacementTypeEnum && $type === PlacementTypeEnum::PRODUCT_TABS) ||
                         (is_string($type) && $type === 'product_tabs') ||
                         ($type?->value === 'product_tabs');

        if (!$isProductTabs && $this->maxItems !== null && $this->currentCount >= $this->maxItems) {
            $this->isLimitReached = true;
            $this->activeBanners = $repo->builder()
                ->where('placement_id', $placementId)
                ->where('status', 'active')
                ->orderBy('priority', $this->contentType === 'product' ? 'asc' : 'desc')
                ->get();
        } else {
            $this->isLimitReached = false;
            $this->activeBanners = [];
        }

        $this->showReplacementModal = true;
        $this->dispatchBrowserEvent('show-reactivate-modal');
    }

    public function reactivateWithReplacement()
    {
        if (!$this->selectedBannerToReplace) {
            $this->alert('error', __('admin::lang.Please select a banner to replace'));
            return;
        }

        if ($this->contentType === 'product') {
            $repo = new AdProductContentRepository(new AdProductContent());
        } else {
            $repo = new AdBannerRepository(new AdBanner());
        }

        $repo->update(
            $this->selectedBannerToReplace,
            ['status' => 'inactive']
        );

        $this->reactivateBanner();
    }

    public function reactivateBanner()
    {
        if ($this->contentType === 'product') {
            $repo = new AdProductContentRepository(new AdProductContent());
        } else {
            $repo = new AdBannerRepository(new AdBanner());
        }

        $maxPriority = $repo->builder()
            ->where('placement_id', $this->placementId)
            ->where('status', 'active')
            ->max('priority') ?? 0;

        $repo->update(
            $this->bannerId,
            [
                'status' => 'active',
                'priority' => $maxPriority + 1,
                'start_date' => $this->start_date ?: null,
                'end_date' => $this->end_date ?: null,
            ]
        );

        $this->reorderActiveItems();

        Cache::forget('home_page_data_ar');
        Cache::forget('home_page_data_en');

        $this->alert('success', __('admin::lang.Content reactivated successfully'));
        $this->emit('contentReactivated');
        $this->emit('refreshContent');
        $this->emit('refreshHistory');
        $this->reset(['showReplacementModal', 'selectedBannerToReplace', 'activeBanners', 'start_date', 'end_date', 'isLimitReached']);
        $this->dispatchBrowserEvent('hide-reactivate-modal');
    }

    private function reorderActiveItems()
    {
        if ($this->contentType === 'product') {
             (new ReorderProductContentsAction())->handle($this->placementId);
        } else {
            (new ReorderActiveBannersAction())->handle($this->placementId);
        }
    }


    public function closeModal()
    {
        $this->reset(['showReplacementModal', 'selectedBannerToReplace', 'start_date', 'end_date', 'isLimitReached']);
        $this->dispatchBrowserEvent('hide-reactivate-modal');
    }

    public function render()
    {
        return view('admin::livewire.ads-content.modal.reactivate');
    }
}
