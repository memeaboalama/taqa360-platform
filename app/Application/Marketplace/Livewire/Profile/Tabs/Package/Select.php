<?php

namespace Application\Marketplace\Livewire\Profile\Tabs\Package;

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
    public array $description = ['en' => null];
    public $commission = null;
    public $terms_en, $terms_ar;
    public $terms_agreed = false;
    public $selected_package = null, $merchant;
    protected $listeners = ['selectPackage'];
    public function mount(FetchSingleMerchantAction $fetchSingleMerchantAction)
    {
        $this->merchant =  $fetchSingleMerchantAction->handle(resolve('_InitialMerchant'));
    }

    public function selectPackage($id, FetchSinglePackageAction $fetchSinglePackageAction): void
    {
        $this->selected_package = $this->merchant->packages()->first()?->id ?? null;
        $this->reset('terms_agreed');
        $this->resetValidation('terms_agreed');
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
        $this->validate(
            ['terms_agreed' => 'accepted'],
            ['terms_agreed.accepted' => __('marketplace::home.please_accept_terms')]
        );
        DB::beginTransaction();
        $selectPackageAction->handle($this->package_id, $this->merchant->id);
        $this->emit('closeModal', '#selectPackage');
        $this->emit('refreshPackages', $this->package_id);
        $this->alert('success', __('marketplace::home.data_updated_successfully'), ['position' => $this->alertPosition()]);
        DB::commit();
    }

    private function alertPosition(): string
    {
        return \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === 'ar'
            ? 'top-start'
            : 'top-end';
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.profile.tabs.package.select');
    }
}
