<?php

namespace Application\Marketplace\Livewire\Menu\Component\Menu\Grid;

use App\Domain\MarketPlace\Actions\Dashboard\Service\FetchServiceAction;
use App\Domain\MarketPlace\Actions\Dashboard\Service\UpdateServiceAction;
use Domain\MarketPlace\Models\MerchantCategory;
use Domain\MarketPlace\Models\MerchantService;
use Illuminate\Support\Facades\DB;
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
    public function mount(FetchServiceAction $fetchServiceAction)
    {
        $this->merchantId = resolve('_InitialMerchant');
        $this->menus = $fetchServiceAction->getByMerchant($this->merchantId);
        $this->menu = resolve('menuInitialization') ?? null;
        if ($this->menu)
            $this->menuSelected = $this->menu?->id;
    }

    public function selectAndSet(MerchantService $menu){
        $this->menu = $menu;
        $this->emit('refreshCategories', $this->menu);
        $this->menuSelected = $this->menu->id;
    }

    public function refreshMenus(FetchServiceAction $fetchServiceAction)
    {
        $this->menus = $fetchServiceAction->getByMerchant($this->merchantId);
    }

    public function render()
    {
        return view('marketplace::livewire.menu.component.menu.grid.menu');
    }
}
