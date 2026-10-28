<?php

namespace Application\Marketplace\Livewire\Service\Modal;

use App\Domain\AppBase\Models\Setting\Currency;
use App\Domain\MarketPlace\Actions\Dashboard\Service\StoreServiceAction;
use Domain\MarketPlace\Models\MerchantCategory;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public   $categories;
    public array $description = ['en' => null];
    public string $status = 'active';
    public array $title = ['en' => null];
    public float $default_price = 0.00;
    public $merchant_category_ids = [];


    protected function rules(): array
    {
        return [
            'merchant_category_ids' => 'required|array',
            'merchant_category_ids.*' => 'required|exists:merchant_categories,id',
            'default_price' => 'required',
            'title' => 'required|array',
            'title.*' => 'required|string|min:2|max:120',
            'description' => 'required|array',
            'description.*' => 'required|string|min:20|max:900',
            'status' => 'required|string|in:active,inactive',
            // 'is_scheduled' => 'required|boolean',
            // 'working_minutes' => 'required',
            // 'requests_per_hour_for_same_service' => 'required',
        ];
    }

    public function mount()
    {
        $this->categories = MerchantCategory::where('merchant_id', auth('merchant')->user()->merchant_id)->pluck('title', 'id')->toArray();
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }


    public function store(StoreServiceAction $storeServiceAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $storeServiceAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createService');
        $this->alert('success', __('marketplace::home.data_saved_successfully'));
        $this->reset('description', 'title',  'merchant_category_ids', 'default_price');
        DB::commit();
    }



    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.service.modal.create');
    }
}
