<?php

namespace Application\Admin\Livewire\AdsContent\Modal;

use App\Domain\AppBase\Actions\AdProductContent\StoreProductContentAction;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Support\Enum\PlacementTypeEnum;
use App\Support\Enum\ProductContentStatusEnum;
use Domain\MarketPlace\Models\Item;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CreateProductContent extends Component
{
    use LivewireAlert;

    public $placementId;
    public $placement;
    public $groups = [];
    public $selected_group_key;
    public $selected_products = [];

    public $start_date;
    public $end_date;
    public $status = 'active';
    public $priority = 0;

    protected $listeners = ['openCreateProductContentModal', 'itemSelected'];

    public function openCreateProductContentModal($placementId)
    {
        $this->placementId = $placementId;
        $this->loadPlacement();
        $this->reset(['selected_products', 'priority', 'selected_group_key', 'start_date', 'end_date']);
        $this->status = 'active';

        if (!empty($this->groups)) {
            $this->selected_group_key = $this->groups[0]['key'];
        }

        $this->emit('openModal', '#createProductContentModal');
    }

    public function loadPlacement()
    {
        $repository = new AdPlacementRepository(new AdPlacement());
        $this->placement = $repository->findById($this->placementId);

        $this->groups = [];
        if ($this->placement->type === PlacementTypeEnum::PRODUCT_TABS && isset($this->placement->settings['groups'])) {
            $this->groups = array_map(function($group) {
                return [
                    'key' => $group['key'],
                    'title' => $group['title']['en'] . ' / ' . $group['title']['ar']
                ];
            }, $this->placement->settings['groups']);
        }
    }

    public function itemSelected($item = null)
    {
        if ($item) {
            if (!collect($this->selected_products)->contains('id', $item['id'])) {
                $this->selected_products[] = $item;
            }
        }
    }

    public function removeProduct($index)
    {
        unset($this->selected_products[$index]);
        $this->selected_products = array_values($this->selected_products);
    }

    protected function rules()
    {
        $rules = [
            'placementId' => 'required|exists:ad_placements,id',
            'selected_products' => 'required|array|min:1',
            'status' => 'required|in:' . implode(',', ProductContentStatusEnum::values()),
            'priority' => 'required|integer|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];

        if ($this->placement && $this->placement->type === PlacementTypeEnum::PRODUCT_TABS) {
            $rules['selected_group_key'] = 'required|string';
        }

        return $rules;
    }

    public function store(StoreProductContentAction $action)
    {
        $this->validate();

        try {
            DB::beginTransaction();

            foreach ($this->selected_products as $productItem) {
                $product = Item::find($productItem['id']);
                if (!$product) continue;

                $data = [
                    'placement_id' => $this->placementId,
                    'product_id' => $product->id,
                    'group_key' => $this->selected_group_key,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'status' => ProductContentStatusEnum::from($this->status),
                    'priority' => $this->priority,
                ];

                $action->handle($data);
            }

            DB::commit();

            if ($this->placement) {
                Cache::forget("ecommerce_sections_{$this->placement->page_id}");
                Cache::forget('home_page_data_en');
                Cache::forget('home_page_data_ar');
            }

            $this->emit('product-content-created');
            $this->emit('closeModal', '#createProductContentModal');
            $this->alert('success', __('admin::lang.Added Successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating product content: ' . $e->getMessage(), ['exception' => $e]);
            $this->alert('error', __('admin::lang.Error occurred') . ': ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('admin::livewire.ads-content.modal.create-product-content');
    }
}
