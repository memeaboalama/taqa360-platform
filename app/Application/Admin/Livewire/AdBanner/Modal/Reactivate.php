<?php

namespace Application\Admin\Livewire\AdBanner\Modal;

use App\Domain\AppBase\Repositories\AdBannerRepository;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Support\Enum\BannerStatusEnum;
use Livewire\Attributes\On;
use Livewire\Component;

class Reactivate extends Component
{
    public $bannerId;
    public $bannerName;
    public $placementId;
    public $maxItems;
    public $currentActiveCount;
    public $showReplacementModal = false;
    public $activeBanners = [];
    public $selectedBannerToReplace;

    protected AdBannerRepository $bannerRepository;
    protected AdPlacementRepository $placementRepository;

    public function boot(AdBannerRepository $bannerRepo, AdPlacementRepository $placementRepo)
    {
        $this->bannerRepository = $bannerRepo;
        $this->placementRepository = $placementRepo;
    }

    #[On('attemptReactivation')]
    public function attemptReactivation($bannerId)
    {
        $this->bannerId = $bannerId;

        $banner = $this->bannerRepository->findByCols(['id' => $bannerId]);
        if (!$banner) {
            $this->alert('error', __('admin::home.Banner not found'));
            return;
        }

        $this->bannerName = $banner->name;
        $this->placementId = $banner->placement_id;

        $placement = $this->placementRepository->findByCols(['id' => $this->placementId]);
        $this->maxItems = $placement->settings['max_items'] ?? null;

        $this->currentActiveCount = $this->bannerRepository->builder()
            ->where('placement_id', $this->placementId)
            ->where('status', BannerStatusEnum::ACTIVE)
            ->count();

        if ($this->maxItems === null || $this->currentActiveCount < $this->maxItems) {
            $this->reactivateBanner();
            return;
        }

        $this->activeBanners = $this->bannerRepository->builder()
            ->where('placement_id', $this->placementId)
            ->where('status', BannerStatusEnum::ACTIVE)
            ->orderByDesc('priority')
            ->get();

        $this->showReplacementModal = true;
        $this->emit('open-modal', 'reactivateModal');
    }

    public function reactivateWithReplacement()
    {
        if (!$this->selectedBannerToReplace) {
            $this->alert('error', __('admin::home.Please select a banner to replace'));
            return;
        }

        $this->bannerRepository->update(
            $this->selectedBannerToReplace,
            ['status' => BannerStatusEnum::INACTIVE]
        );

        $this->reactivateBanner();
    }

    private function reactivateBanner()
    {
        $this->bannerRepository->update(
            $this->bannerId,
            ['status' => BannerStatusEnum::ACTIVE]
        );

        $this->emit('close-modal', 'reactivateModal');
        $this->emit('banner-reactivated');
        $this->alert('success', __('admin::home.Banner reactivated successfully'));

        $this->reset(['showReplacementModal', 'selectedBannerToReplace', 'activeBanners']);
    }

    public function render()
    {
        return view('admin::livewire.ad-banner.modal.reactivate');
    }
}
