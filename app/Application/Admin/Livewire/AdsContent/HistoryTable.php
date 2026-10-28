<?php

namespace Application\Admin\Livewire\AdsContent;

use App\Domain\AppBase\Models\AdBanner;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Models\AdProductContent;
use App\Domain\AppBase\Repositories\AdBannerRepository;
use App\Domain\AppBase\Repositories\AdProductContentRepository;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use Livewire\Component;

class HistoryTable extends Component
{
    public $placementId;
    public $placement;
    public $contents = [];
    public $statusFilter = 'all';

    protected $listeners = [
        'refreshHistory',
        'banner-created' => 'refreshHistory',
        'banner-updated' => 'refreshHistory',
        'banner-deleted' => 'refreshHistory',
        'product-content-created' => 'refreshHistory',
        'product-content-updated' => 'refreshHistory',
        'product-content-deleted' => 'refreshHistory',
    ];

    public function mount($placementId)
    {
        $this->placementId = $placementId;
        $this->loadPlacement();
        $this->loadContents();
    }

    public function loadPlacement()
    {
        $repository = new AdPlacementRepository(new AdPlacement());
        $this->placement = $repository->findById($this->placementId);
    }

    public function loadContents()
    {
        $isProductType = in_array($this->placement->type->value, ['product_tabs', 'product_slider', 'product_sidebar']);

        if ($isProductType) {
            $productRepo = new AdProductContentRepository(new AdProductContent());
            $query = $productRepo->builder(
                order: 'desc',
                orderCol: 'updated_at',
                condition: ['placement_id' => $this->placementId]
            );

            if ($this->statusFilter === 'all') {
                $query->whereIn('status', ['inactive', 'expired' , 'draft']);
            } else {
                $query->where('status', $this->statusFilter);
            }

            $this->contents = $query
                ->with('product')
                ->get()
                ->map(function($content) {
                    $content->content_type = 'product';
                    $content->displayable_product = $content->product;
                    return $content;
                });
        } else {
            $bannerRepo = new AdBannerRepository(new AdBanner());
            $query = $bannerRepo->builder(
                order: 'desc',
                orderCol: 'updated_at',
                condition: ['placement_id' => $this->placementId]
            );

            if ($this->statusFilter === 'all') {
                $query->whereIn('status', ['inactive', 'expired']);
            } else {
                $query->where('status', $this->statusFilter);
            }

            $this->contents = $query
                ->get()
                ->map(function($banner) {
                    $banner->content_type = 'banner';
                    return $banner;
                });
        }
    }

    public function updatedStatusFilter()
    {
        $this->loadContents();
    }

    public function refreshHistory()
    {
        $this->loadContents();
    }

    public function reactivate($contentId, $type = 'banner')
    {
        $this->emit('openReactivateModal', $contentId, $this->placementId, $type);
    }

    public function openReactivateModal($contentId, $type = 'banner')
    {
        $this->emit('openReactivateModal', $contentId, $this->placementId, $type);
    }

    public function render()
    {
        return view('admin::livewire.ads-content.history-table');
    }
}
