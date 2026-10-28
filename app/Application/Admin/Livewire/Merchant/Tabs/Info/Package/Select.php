<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Info\Package;

use App\Domain\Admin\Actions\Dashboard\Merchant\FetchSingleMerchantAction;
use App\Domain\Admin\Actions\Dashboard\Package\FetchPackageAction;
use App\Domain\Admin\Actions\Dashboard\Package\FetchSinglePackageAction;
use App\Domain\Admin\Actions\Dashboard\Package\SelectPackageAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Select extends Component
{
    use LivewireAlert;
    public int|null $package_id;
    public $package;
    public array $title = ['en' => null];
    public $terms_en, $terms_ar;
    public $selected_package = null, $merchant;
    protected $listeners = ['selectPackage'];
    public function mount(int $merchant_id, FetchSingleMerchantAction $fetchSingleMerchantAction)
    {
        $this->merchant =  $fetchSingleMerchantAction->handle($merchant_id);
    }

    public function selectPackage($id, FetchSinglePackageAction $fetchSinglePackageAction): void
    {
        $this->selected_package = $this->merchant->packages()->first()?->id ?? null;
        $this->package = $fetchSinglePackageAction->handle($id);
        if ($this->package) {
            $this->title = $this->package->getTranslations('title');
            $this->description = $this->package->getTranslations('description');
            $this->package_id = $this->package->id;
            $this->terms_en = $this->package->terms_en;
            $this->terms_ar = $this->package->terms_ar;
            $this->commission = $this->package->commission;
            $this->emit('openModal', '#selectPackage');
        }
    }

    public function attachPackage(SelectPackageAction $selectPackageAction, FetchPackageAction $fetchPackageAction): void
    {
        DB::beginTransaction();
        $selectPackageAction->handle($this->package_id, $this->merchant->id);
        $this->emit('closeModal', '#selectPackage');
        $this->emit('refreshPackages', $this->package_id);
        $this->alert('success', __('admin::home.data_updated_successfully'));
        DB::commit();
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.info.package.select');
    }
}
