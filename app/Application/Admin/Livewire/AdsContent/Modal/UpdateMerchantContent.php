<?php

namespace Application\Admin\Livewire\AdsContent\Modal;

use App\Domain\AppBase\Actions\AdMerchantContent\UpdateMerchantContentAction;
use App\Domain\AppBase\Models\AdMerchantContent;
use App\Domain\AppBase\Repositories\AdMerchantContentRepository;
use App\Support\Enum\ProductContentStatusEnum;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class UpdateMerchantContent extends Component
{
    use LivewireAlert;

    public $merchantContentId;
    public $merchantContent;
    public $placement;

    public $merchant_name;
    public $merchant_logo;

    public $status;
    public $priority;
    public $start_date;
    public $end_date;

    protected $listeners = ['openUpdateMerchantContentModal'];

    public function openUpdateMerchantContentModal($merchantContentId)
    {
        $repo = new AdMerchantContentRepository(new AdMerchantContent());
        $this->merchantContent = $repo->findById($merchantContentId);

        if (!$this->merchantContent) {
            $this->alert('error', 'Merchant content not found');
            return;
        }

        $this->merchantContentId = $merchantContentId;
        $this->placement = $this->merchantContent->placement;

        $this->status = $this->merchantContent->status->value;
        $this->priority = $this->merchantContent->priority;
        $this->start_date = $this->merchantContent->start_date ? $this->merchantContent->start_date->format('Y-m-d') : null;
        $this->end_date = $this->merchantContent->end_date ? $this->merchantContent->end_date->format('Y-m-d') : null;

        $this->merchant_name = $this->merchantContent->merchant?->company_name ?? 'N/A';
        $this->merchant_logo = $this->merchantContent->merchant?->logo ?? null;

        $this->emit('openModal', '#updateMerchantContentModal');
    }

    protected function rules()
    {
        $rules = [
            'merchantContentId' => 'required|exists:ad_merchant_contents,id',
            'status' => 'required|in:' . implode(',', ProductContentStatusEnum::values()),
            'priority' => 'required|integer|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];

        return $rules;
    }

    public function update(UpdateMerchantContentAction $action)
    {
        $this->validate();

        $data = [
            'status' => ProductContentStatusEnum::from($this->status),
            'priority' => $this->priority,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];

        try {
            $action->handle($this->merchantContentId, $data);

            if ($this->placement) {
                Cache::forget("ecommerce_banners_{$this->placement->key}_en");
                Cache::forget("ecommerce_banners_{$this->placement->key}_ar");
                Cache::forget('home_page_data_en');
                Cache::forget('home_page_data_ar');
            }

            $this->emit('merchant-content-updated');
            $this->emit('closeModal', '#updateMerchantContentModal');
            $this->alert('success', __('admin::lang.Updated Successfully'));
        } catch (\Exception $e) {
            Log::error('Error updating merchant content: ' . $e->getMessage(), ['exception' => $e]);
            $this->alert('error', __('admin::lang.Error occurred'));
        }
    }

    public function render()
    {
        return view('admin::livewire.ads-content.modal.update-merchant-content');
    }
}
