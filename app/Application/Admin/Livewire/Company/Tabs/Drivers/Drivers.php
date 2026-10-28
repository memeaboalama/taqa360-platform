<?php

namespace Application\Admin\Livewire\Company\Tabs\Drivers;

use Livewire\Component;

class Drivers extends Component
{
    public $companyId;
    public function mount(int $companyId): void
    {
        $this->companyId = $companyId;
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.company.tabs.drivers.drivers');
    }
}
