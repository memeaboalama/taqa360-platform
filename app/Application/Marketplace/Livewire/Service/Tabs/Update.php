<?php

namespace Application\Marketplace\Livewire\Service\Tabs;

use App\Domain\MarketPlace\Actions\Dashboard\Service\UpdateServiceAction;
use Domain\MarketPlace\Models\MerchantCategory;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public $service;
    public array $description = ['en' => null];
    public string $status = 'active';
    public bool $is_scheduled = true;
    public array $title = ['en' => null];
    public string $default_price;
    public int $merchant_category_id,
        $working_minutes;

    public   $categories = [];

    public $merchant_category_ids = [];
    public function mount($service)
    {
        $this->service = $service;
        $this->title = $service->getTranslations('title');
        $this->description = $service->getTranslations('description');
        $this->default_price = $service->default_price;
        $this->status = $service->status;
        $this->merchant_category_ids = $service->categories()->pluck('merchant_category_id')->toArray();
        $this->service_id = $service->id;
        $this->categories = MerchantCategory::where('merchant_id', auth('merchant')->user()->merchant_id)->pluck('title', 'id')->toArray();
    }

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
            'description.ar' => 'nullable',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }



    public function updateService(UpdateServiceAction $updateServiceAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updateServiceAction->handle($this->service->id, $data);
        $this->alert('success', 'Service Updated Successfully');
        DB::commit();
    }

    public function render()
    {
        return view('marketplace::livewire.service.tabs.update');
    }
}
