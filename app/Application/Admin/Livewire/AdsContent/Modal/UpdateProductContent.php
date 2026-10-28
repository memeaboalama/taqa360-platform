<?php

namespace Application\Admin\Livewire\AdsContent\Modal;

use App\Domain\AppBase\Actions\AdProductContent\UpdateProductContentAction;
use App\Domain\AppBase\Models\AdProductContent;
use App\Domain\AppBase\Repositories\AdProductContentRepository;
use App\Support\Enum\PlacementTypeEnum;
use App\Support\Enum\ProductContentStatusEnum;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class UpdateProductContent extends Component
{
    use LivewireAlert;

    public $productContentId;
    public $productContent;
    public $placement;

    public $groups = [];
    public $selected_group_key;

    public $product_title;
    public $product_image;

    public $status;
    public $priority;
    public $start_date;
    public $end_date;

    protected $listeners = ['openUpdateProductContentModal'];

    public function openUpdateProductContentModal($productContentId)
    {
        $repo = new AdProductContentRepository(new AdProductContent());
        $this->productContent = $repo->findById($productContentId);

        if (!$this->productContent) {
            $this->alert('error', 'Product content not found');
            return;
        }

        $this->productContentId = $productContentId;
        $this->placement = $this->productContent->placement;

        $this->status = $this->productContent->status->value;
        $this->priority = $this->productContent->priority;
        $this->start_date = $this->productContent->start_date ? $this->productContent->start_date->format('Y-m-d') : null;
        $this->end_date = $this->productContent->end_date ? $this->productContent->end_date->format('Y-m-d') : null;

        $this->product_title = $this->productContent->product?->getTranslation('title', app()->getLocale()) ?? 'N/A';
        $this->product_image = $this->productContent->product?->image ?? null;

        $this->loadGroups();
        $this->selected_group_key = $this->productContent->group_key;

        $this->emit('openModal', '#updateProductContentModal');
    }

    public function loadGroups()
    {
        $this->groups = [];


        if (isset($this->placement->settings['groups']) && is_array($this->placement->settings['groups'])) {
            $this->groups = array_map(function($group) {
                return [
                    'key' => $group['key'],
                    'title' => ($group['title']['en'] ?? '') . ' / ' . ($group['title']['ar'] ?? $group['key'])
                ];
            }, $this->placement->settings['groups']);
        }
    }

    protected function rules()
    {
        $rules = [
            'productContentId' => 'required|exists:ad_product_contents,id',
            'status' => 'required|in:' . implode(',', ProductContentStatusEnum::values()),
            'priority' => 'required|integer|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];

        if ($this->placement && $this->placement->type === PlacementTypeEnum::PRODUCT_TABS) {
            $rules['selected_group_key'] = 'nullable|string';
        }

        return $rules;
    }

    public function update(UpdateProductContentAction $action)
    {
        $this->validate();

        $data = [
            'status' => ProductContentStatusEnum::from($this->status),
            'priority' => $this->priority,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'group_key' => $this->selected_group_key,
        ];

        try {
            $action->handle($this->productContentId, $data);

            if ($this->placement) {
                Cache::forget("ecommerce_sections_{$this->placement->page_id}");
                Cache::forget('home_page_data_en');
                Cache::forget('home_page_data_ar');
            }

            $this->emit('product-content-updated');
            $this->emit('closeModal', '#updateProductContentModal');
            $this->alert('success', __('admin::lang.Updated Successfully'));
        } catch (\Exception $e) {
            Log::error('Error updating product content: ' . $e->getMessage(), ['exception' => $e]);
            $this->alert('error', __('admin::lang.Error occurred'));
        }
    }

    public function render()
    {
        return view('admin::livewire.ads-content.modal.update-product-content');
    }
}
