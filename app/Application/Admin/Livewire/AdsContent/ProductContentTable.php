<?php

namespace Application\Admin\Livewire\AdsContent;

use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Models\AdProductContent;
use App\Domain\AppBase\Repositories\AdProductContentRepository;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Support\Enum\PlacementTypeEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ProductContentTable extends Component
{
    public $placementId;
    public $placement;
    public $productContents = [];
    public $statusFilter = 'all';
    public $groupFilter = 'all';
    public $viewMode = 'table';

    protected $listeners = [
        'refreshProductContent',
        'product-content-created' => 'refreshProductContent',
        'product-content-updated' => 'refreshProductContent',
        'product-content-deleted' => 'refreshProductContent',
    ];

    public function toggleViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function mount($placementId)
    {
        $this->placementId = $placementId;
        $this->loadPlacement();
        $this->loadProductContents();
    }

    public function loadPlacement()
    {
        $repository = new AdPlacementRepository(new AdPlacement());
        $this->placement = $repository->findById($this->placementId);
    }

    public function loadProductContents()
    {
        $repository = new AdProductContentRepository(new AdProductContent());

        $query = $repository->builder(
            order: 'asc',
            orderCol: 'priority',
            condition: ['placement_id' => $this->placementId]
        );

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->groupFilter !== 'all' && $this->placement->type === PlacementTypeEnum::PRODUCT_TABS) {
            $query->where('group_key', $this->groupFilter);
        }

        $this->productContents = $query
            ->with('product')
            ->orderBy('priority', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function updatedStatusFilter()
    {
        $this->loadProductContents();
    }

    public function updatedGroupFilter()
    {
        $this->loadProductContents();
    }

    public function refreshProductContent()
    {
        $this->loadProductContents();
    }

    public function reorder($order)
    {
        try {
            DB::beginTransaction();

            foreach ($order as $item) {
                AdProductContent::where('id', $item['id'])
                    ->update(['priority' => $item['priority']]);
            }

            DB::commit();

            $this->productContents = $this->productContents->map(function($content) use ($order) {
                foreach ($order as $item) {
                    if ($content->id == $item['id']) {
                        $content->priority = $item['priority'];
                        break;
                    }
                }
                return $content;
            })->sortBy('priority')->values();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product content reorder failed: ' . $e->getMessage());
            $this->loadProductContents();
        }
    }

    public function openCreateModal()
    {
        $this->emit('openCreateProductContentModal', $this->placementId);
    }

    public function openUpdateModal($productContentId)
    {
        $this->emit('openUpdateProductContentModal', $productContentId);
    }

    public function openDeleteModal($productContentId)
    {
        $this->emit('openDeleteProductContentModal', $productContentId);
    }

    public function getGroupsProperty()
    {
        if ($this->placement->type !== PlacementTypeEnum::PRODUCT_TABS) {
            return [];
        }

        return $this->placement->settings['groups'] ?? [];
    }

    public function render()
    {
        return view('admin::livewire.ads-content.product-content-table');
    }
}
