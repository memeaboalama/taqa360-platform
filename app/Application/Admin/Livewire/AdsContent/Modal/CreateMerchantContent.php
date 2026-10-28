<?php

namespace Application\Admin\Livewire\AdsContent\Modal;

use App\Domain\AppBase\Actions\AdMerchantContent\StoreMerchantContentAction;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Support\Enum\ProductContentStatusEnum;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CreateMerchantContent extends Component
{
    use LivewireAlert;

    public $placementId;
    public $placement;
    public $selected_merchants = [];

    public $start_date;
    public $end_date;
    public $status = 'active';
    public $priority = 0;

    protected $listeners = ['openCreateMerchantContentModal', 'merchantSelected' => 'itemSelected'];

    public function openCreateMerchantContentModal($placementId)
    {
        $this->placementId = $placementId;
        $this->loadPlacement();
        $this->reset(['selected_merchants', 'priority', 'start_date', 'end_date']);
        $this->status = 'active';

        $this->emit('openModal', '#createMerchantContentModal');
    }

    public function loadPlacement()
    {
        $repository = new AdPlacementRepository(new AdPlacement());
        $this->placement = $repository->findById($this->placementId);
    }

    public function itemSelected($item = null)
    {
        if ($item && is_array($item) && isset($item['id'])) {
            if (!collect($this->selected_merchants)->contains('id', $item['id'])) {
                $merchant = Merchant::find($item['id']);
                if ($merchant) {
                    $this->selected_merchants[] = [
                        'id' => $merchant->id,
                        'title' => $merchant->getTranslation('company_name', app()->getLocale()),
                        'image' => $merchant->logo ?? '/default.jpg'
                    ];
                }
            }
        }
    }

    public function removeMerchant($index)
    {
        unset($this->selected_merchants[$index]);
        $this->selected_merchants = array_values($this->selected_merchants);
    }

    protected function rules()
    {
        $rules = [
            'placementId' => 'required|exists:ad_placements,id',
            'selected_merchants' => 'required|array|min:1',
            'status' => 'required|in:' . implode(',', ProductContentStatusEnum::values()),
            'priority' => 'required|integer|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];

        return $rules;
    }

    public function store(StoreMerchantContentAction $action)
    {
        $this->validate();

        try {
            DB::beginTransaction();

            foreach ($this->selected_merchants as $merchantItem) {
                $data = [
                    'placement_id' => $this->placementId,
                    'merchant_id' => $merchantItem['id'],
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'status' => ProductContentStatusEnum::from($this->status),
                    'priority' => $this->priority,
                ];

                $action->handle($data);
            }

            DB::commit();

            if ($this->placement) {
                Cache::forget("ecommerce_banners_{$this->placement->key}_en");
                Cache::forget("ecommerce_banners_{$this->placement->key}_ar");
                Cache::forget('home_page_data_en');
                Cache::forget('home_page_data_ar');
            }

            $this->emit('merchant-content-created');
            $this->emit('closeModal', '#createMerchantContentModal');
            $this->alert('success', __('admin::lang.Added Successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating merchant content: ' . $e->getMessage(), ['exception' => $e]);
            $this->alert('error', __('admin::lang.Error occurred') . ': ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('admin::livewire.ads-content.modal.create-merchant-content');
    }
}
