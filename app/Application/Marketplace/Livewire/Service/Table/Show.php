<?php

namespace Application\Marketplace\Livewire\Service\Table;
use App\Domain\AppBase\Models\Setting\Currency;
use App\Domain\MarketPlace\Actions\Dashboard\Service\UpdateServiceAction;
use Domain\MarketPlace\Models\MerchantCategory;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Show extends Component
{
    public $service, $addon_categories;
    public array $description = ['en' => null];
    public string $status = 'active';
    public bool $is_scheduled = true;
    public array $title = ['en' => null];
    public string $default_price;
    public int $currency_id,
        $merchant_category_id,
        $working_minutes,
        $requests_per_hour_for_same_service;
    public $currencies, $categories;

    public function mount($service, $addon_categories)
    {
        $this->service = $service;
        $this->addon_categories = $addon_categories;
        $this->title = $service->getTranslations('title');
        $this->description = $service->getTranslations('description');
        $this->working_minutes = $service->working_minutes;
        $this->default_price = $service->default_price;
        $this->requests_per_hour_for_same_service = $service->requests_per_hour_for_same_service;
        $this->status = $service->status;
        $this->is_scheduled = $service->is_scheduled;
        $this->merchant_category_id = $service->categories()->first()->id;
        $this->service_id = $service->id;
        $this->categories = MerchantCategory::where('merchant_id', auth('merchant')->user()->merchant_id)->get();
    }

    public function render()
    {
        return view('marketplace::livewire.service.table.show', [
            'service' => $this->service,
            'addon_categories' => $this->addon_categories,
        ]);
    }
}
