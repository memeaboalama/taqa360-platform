<?php

namespace Application\Marketplace\Livewire\Service\Tabs;

use Domain\MarketPlace\Models\MerchantAddonCategory;
use Domain\MarketPlace\Models\MerchantService;
use Livewire\Component;

class Addons extends Component
{
    public $service;
    protected $listeners = ['refreshAddons' => 'render', 'refresh_addons' => 'refresh_addons'];


    public function render()
    {
        return view('marketplace::livewire.service.tabs.addons',[
            'service' => $this->service,
        ]);
    }

    public function refresh_addons(MerchantService $service)
    {
        $this->service = $service;
    }
}
