<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Info;

use App\Domain\Admin\Actions\Dashboard\Package\FetchPackageAction;
use App\Domain\Admin\Actions\Dashboard\Package\FetchSinglePackageAction;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Packages extends Component
{
    use LivewireAlert;
    public mixed $packages = [];
    public $selected_package = null;
    protected $listeners = ['refreshPackages'];
    public function mount(int $merchant_id, FetchPackageAction $fetchPackageAction, FetchSinglePackageAction $fetchSinglePackageAction)
    {
        $this->selected_package = $fetchSinglePackageAction->fetchByMerchant($merchant_id) ?? null;
        $this->packages = $fetchPackageAction->active();
    }

    public function refreshPackages($selected)
    {
        $this->selected_package = $selected;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.info.packages');
    }
}
