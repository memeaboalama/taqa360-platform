<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Info;

use Livewire\Component;

class Groups extends Component
{
    public $merchantId;
    public function mount(int $merchantId): void
    {
        $this->merchantId = $merchantId;
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.info.groups');
    }
}
