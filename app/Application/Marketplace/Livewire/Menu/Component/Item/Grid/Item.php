<?php

namespace Application\Marketplace\Livewire\Menu\Component\Item\Grid;

use Domain\MarketPlace\Models\MerchantService;
use Livewire\Component;

class Item extends Component
{
    public $menu, $category_id = null, $menu_id = null;
    public $items = [];
    public $listeners = ['refreshItems'];
    public function mount()
    {
        $this->menu = resolve('menuInitialization') ?? null;
        if ($this->menu)
        {
            $this->menu_id = $this->menu->id;
            $this->category_id = $this->menu->categories()->first()?->id;
            $this->setItems();
        }

    }

    public function refreshItems(int $category_id, MerchantService $menu)
    {
        $this->menu = $menu;
        $this->menu_id = $this->menu->id;
        $this->category_id = $category_id;
        $this->setItems();
        $this->emit('refreshData', $category_id, $menu);
    }

    private function setItems()
    {
        $this->items = $this->menu
            ->categories()
            ->where('merchant_category_id', $this->category_id)
            ?->first()
            ?->items()
            ->get();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.menu.component.item.grid.item', [
            'menu_id' => $this->menu_id
        ]);
    }
}
