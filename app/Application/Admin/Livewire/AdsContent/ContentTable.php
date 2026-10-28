<?php

namespace Application\Admin\Livewire\AdsContent;

use App\Domain\AppBase\Models\AdBanner;
use App\Domain\AppBase\Models\AdMerchantContent;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Models\AdProductContent;
use App\Domain\AppBase\Repositories\AdBannerRepository;
use App\Domain\AppBase\Repositories\AdMerchantContentRepository;
use App\Domain\AppBase\Repositories\AdProductContentRepository;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Support\Enum\BannerStatusEnum;
use App\Support\Enum\MerchantContentStatusEnum;
use App\Support\Enum\PlacementTypeEnum;
use App\Support\Enum\ProductContentStatusEnum;
use Domain\AppBase\Repositories\AdCategoryContentRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ContentTable extends Component
{
    public $placementId;
    public $statusFilter = 'all';
    public $viewMode = 'table';

    protected $listeners = [
        'refreshContent',
        'banner-created' => 'refreshContent',
        'banner-updated' => 'refreshContent',
        'banner-deleted' => 'refreshContent',
        'product-content-created' => 'refreshContent',
        'product-content-updated' => 'refreshContent',
        'product-content-deleted' => 'refreshContent',
        'merchant-content-created' => 'refreshContent',
        'merchant-content-updated' => 'refreshContent',
        'merchant-content-deleted' => 'refreshContent',
        'contentReactivated' => 'refreshContent'
    ];

    public function toggleViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function mount($placementId)
    {
        $this->placementId = $placementId;
    }

    public function getPlacementProperty()
    {
        $repository = new AdPlacementRepository(new AdPlacement());
        return $repository->findById($this->placementId);
    }

    public function getStatusOptionsProperty(): array
    {
        if ($this->placement->type->isMerchantSection()) {
            return MerchantContentStatusEnum::cases();
        }

        if (in_array($this->placement->type->value, ['product_tabs', 'product_slider', 'product_sidebar'])) {
            return ProductContentStatusEnum::cases();
        }

        return BannerStatusEnum::cases();
    }


    public function getContentsProperty()
    {
        $isMerchantType = $this->placement->type->isMerchantSection();
        $isProductType = in_array($this->placement->type->value, ['product_tabs', 'product_slider', 'product_sidebar']);
        $isCategoryType = $this->placement->type->isCategorySection();

        if ($isMerchantType) {
            $merchantRepo = new AdMerchantContentRepository(new AdMerchantContent());
            $query = $merchantRepo->builder(
                order: 'asc',
                orderCol: 'priority',
                condition: ['placement_id' => $this->placementId]
            );

            if ($this->statusFilter !== 'all') {
                $query->where('status', $this->statusFilter);
            }

            return $query
                ->with('merchant')
                ->orderBy('priority', 'asc')
                ->orderBy('id', 'asc')
                ->get()
                ->map(function($content) {
                    $content->content_type = 'merchant';
                    $content->displayable_merchant = $content->merchant;
                    return $content;
                });
        } elseif ($isProductType) {
            $productRepo = new AdProductContentRepository(new AdProductContent());
            $query = $productRepo->builder(
                order: 'asc',
                orderCol: 'priority',
                condition: ['placement_id' => $this->placementId]
            );

            if ($this->statusFilter !== 'all') {
                $query->where('status', $this->statusFilter);
            }

            return $query
                ->with('product')
                ->orderBy('priority', 'asc')
                ->orderBy('id', 'asc')
                ->get()
                ->map(function($content) {
                    $content->content_type = 'product';
                    $content->displayable_product = $content->product;
                    return $content;
                });
        } elseif ($isCategoryType) {
            $categoryRepo = new AdCategoryContentRepository(new \Domain\AppBase\Models\AdCategoryContent());
            $query = $categoryRepo->builder(
                order: 'asc',
                orderCol: 'priority',
                condition: ['placement_id' => $this->placementId]
            );

            if ($this->statusFilter !== 'all') {
                $query->where('status', $this->statusFilter);
            }

            return $query
                ->with('category')
                ->orderBy('priority', 'asc')
                ->orderBy('id', 'asc')
                ->get()
                ->map(function($content) {
                    $content->content_type = 'category';
                    $content->displayable_category = $content->category;
                    return $content;
                });
        } else {
            $bannerRepo = new AdBannerRepository(new AdBanner());
            $query = $bannerRepo->builder(
                order: 'asc',
                orderCol: 'priority',
                condition: ['placement_id' => $this->placementId]
            );

            if ($this->statusFilter !== 'all') {
                $query->where('status', $this->statusFilter);
            }

            return $query
                ->orderBy('priority', 'asc')
                ->orderBy('id', 'asc')
                ->get()
                ->map(function($banner) {
                    $banner->content_type = 'banner';
                    return $banner;
                });
        }
    }

    public function updatedStatusFilter()
    {
    }

    public function refreshContent()
    {
    }

    public function reorder($order)
    {
        try {
            DB::beginTransaction();

            $isMerchantType = $this->placement->type->isMerchantSection();
            $isProductType = in_array($this->placement->type->value, ['product_tabs', 'product_slider', 'product_sidebar']);
            $isCategoryType = $this->placement->type->isCategorySection();

            foreach ($order as $item) {
                if ($isMerchantType) {
                    AdMerchantContent::where('id', $item['id'])
                        ->update(['priority' => $item['priority']]);
                } elseif ($isProductType) {
                    AdProductContent::where('id', $item['id'])
                        ->update(['priority' => $item['priority']]);
                } elseif ($isCategoryType) {
                    \Domain\AppBase\Models\AdCategoryContent::where('id', $item['id'])
                        ->update(['priority' => $item['priority']]);
                } else {
                    AdBanner::where('id', $item['id'])
                        ->update(['priority' => $item['priority']]);
                }
            }

            Cache::forget('home_page_data_ar');
            Cache::forget('home_page_data_en');

            DB::commit();


        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Content reorder failed: ' . $e->getMessage());
        }
    }

    public function openCreateModal()
    {
        if ($this->placement->type->isMerchantSection()) {
            $this->emit('openCreateMerchantContentModal', $this->placementId);
            return;
        }

        if (in_array($this->placement->type->value, [
            'product_tabs',
            'product_slider',
            'product_sidebar'
        ])) {
            $this->emit('openCreateProductContentModal', $this->placementId);
            return;
        }

        if ($this->placement->type->isCategorySection()) {
            $this->emit('openCreateCategoryContentModal', $this->placementId);
            return;
        }

        $this->emit('openCreateContentModal', $this->placementId);
    }

    public function openUpdateModal($contentId)
    {
        if ($this->placement->type->isMerchantSection()) {
            $this->emit('openUpdateMerchantContentModal', $contentId);
            return;
        }

        if (in_array($this->placement->type->value, [
            'product_tabs',
            'product_slider',
            'product_sidebar'
        ])) {
            $this->emit('openUpdateProductContentModal', $contentId);
            return;
        }

        if ($this->placement->type->isCategorySection()) {
            return;
        }

        $this->emit('openUpdateContentModal', $contentId);
    }

    public function openDeleteModal($contentId)
    {
        if ($this->placement->type->isMerchantSection()) {
            $this->emit('openDeleteMerchantContentModal', $contentId);
            return;
        }

        if (in_array($this->placement->type->value, [
            'product_tabs',
            'product_slider',
            'product_sidebar'
        ])) {
            $this->emit('openDeleteProductContentModal', $contentId);
            return;
        }

        if ($this->placement->type->isCategorySection()) {
             $this->emit('openDeleteCategoryContentModal', $contentId);
             return;
        }

        $this->emit('openDeleteContentModal', $contentId);
    }

    public function render()
    {
        return view('admin::livewire.ads-content.content-table', [
            'contents' => $this->contents,
            'placement' => $this->placement,
            'statusOptions' => $this->statusOptions
        ]);
    }
}

