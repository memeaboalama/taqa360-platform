<?php

namespace Application\Marketplace\Livewire\Profile\Tabs;

use App\Domain\Admin\Actions\Dashboard\Merchant\FetchSingleMerchantAction;
use App\Domain\Admin\Actions\Dashboard\Package\FetchPackageAction;
use Illuminate\Database\Eloquent\Collection;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Packages extends Component
{
    use LivewireAlert;
    public mixed $packages = [];
    public $selected_package = null;
    protected $listeners = ['refreshPackages'];
    public function mount(FetchPackageAction $fetchPackageAction, FetchSingleMerchantAction $fetchSingleMerchantAction)
    {
        $this->merchant =  $fetchSingleMerchantAction->handle(resolve('_InitialMerchant'));
        $this->selected_package =  $this->merchant->packages()->first()?->id ?? null;
        $this->packages = $fetchPackageAction->active();
    }

    public function refreshPackages($selected)
    {
        $this->selected_package = $selected;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.profile.tabs.packages');
    }
}
