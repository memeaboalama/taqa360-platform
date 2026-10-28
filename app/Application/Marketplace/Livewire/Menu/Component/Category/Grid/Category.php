<?php

namespace Application\Marketplace\Livewire\Menu\Component\Category\Grid;

use Domain\MarketPlace\Models\MerchantService;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Category extends Component
{
    use LivewireAlert;
    public $menu, $menu_id, $categorySelected = null;
    public $categories = [];
    public $listeners = ['setCategoryItems', 'refreshCategories'];
    public function mount()
    {
        $this->menu = resolve('menuInitialization') ?? null;
        if ($this->menu)
            $this->menu_id = $this->menu?->id;
        $this->setCategories();
    }

    public function refreshCategories(MerchantService $menu){
        $this->menu = $menu;
        $this->menu_id = $this->menu?->id;
        $this->setCategories();
        $this->emit('refreshItems', $this->categorySelected, $menu);
    }

    public function setCategoryItems(int $category_id){
        $this->emit('refreshItems', $category_id, $this->menu);
        $this->setCategories();
        $this->categorySelected = $category_id;
        $this->emit('setCategoryId', $this->categorySelected);
    }

    private function setCategories()
    {
        if ($this->menu)
        {
            $current = $this->categorySelected;
            if ($this->menu->categories()->first())
            {
                $this->categorySelected = $this->menu->categories->contains('id', $current)
                    ? $current
                    : $this->menu->categories()->first()?->id;
            }else{
                $this->categorySelected = null;
            }
            $this->categories = $this->menu->categories;
        }
    }
    public function render()
    {
        return view('marketplace::livewire.menu.component.category.grid.category', [
            'menu_id' => $this->menu_id
        ]);
    }
}
