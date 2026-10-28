<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Menu\Component\Menu\Grid;

use App\Domain\Admin\Actions\Dashboard\Menu\Menu\FetchMenuAction;
use App\Domain\MarketPlace\Repositories\Service\MerchantServiceRepository;
use Domain\MarketPlace\Models\MerchantService;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Menu extends Component
{
    use LivewireAlert;
    public $menus = [];
    public $menuSelected = null;
    public $menu = null;
    public $merchantId;
    public $listeners = ['selectAndSet', 'refreshMenus'];
    public function mount(int $merchantId, FetchMenuAction $fetchMenuAction, MerchantServiceRepository $merchantServiceRepository)
    {
        $this->merchantId = $merchantId;
        $this->menus = $fetchMenuAction->getByMerchant($this->merchantId);
        $this->menu = $merchantServiceRepository->first($this->merchantId);
        if ($this->menu)
            $this->menuSelected = $this->menu?->id;
    }

    public function selectAndSet(MerchantService $menu){
        $this->menu = $menu;
        $this->emit('refreshCategories', $this->menu);
        $this->menuSelected = $this->menu->id;
    }

    public function refreshMenus(FetchMenuAction $fetchMenuAction)
    {
        $this->menus = $fetchMenuAction->getByMerchant($this->merchantId);
    }

    public function render()
    {
        return view('admin::livewire.merchant.tabs.menu.component.menu.grid.menu');
    }
}
