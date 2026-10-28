<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Menu\Component\Menu\Modal;

use App\Domain\Admin\Actions\Dashboard\Menu\Menu\FetchSingleMenuAction;
use App\Domain\Admin\Actions\Dashboard\Menu\Menu\UpdateMenuAction;
use App\Domain\MarketPlace\Actions\Dashboard\Service\UpdateServiceAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Accept extends Component
{
    use LivewireAlert;
    public $updated = "";
    public $menu, $merchantId;
    protected $listeners = ['acceptMenu'];

    public function mount(int $merchant_id)
    {
        $this->merchantId = $merchant_id;
    }

    public function acceptMenu($id, FetchSingleMenuAction $fetchSingleMenuAction): void
    {
        $menu = $fetchSingleMenuAction->handle($id, $this->merchantId);
        if ($menu) {
            $this->menu_id = $menu->id;
            $this->emit('openModal', '#acceptMenu');
        }
    }

    public function accept(UpdateMenuAction $updateMenuAction): void
    {
        DB::beginTransaction();
        $updateMenuAction->accept($this->menu_id);
        $this->emit('closeModal', '#acceptMenu');
        $this->emit('selectAndSet', $this->menu_id);
        $this->emit('refreshMenus');
        $this->alert('success', __('admin::home.data_updated_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.menu.component.menu.modal.accept');
    }
}
