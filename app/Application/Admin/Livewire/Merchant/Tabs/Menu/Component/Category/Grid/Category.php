<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Menu\Component\Category\Grid;

use App\Domain\MarketPlace\Repositories\Service\MerchantServiceRepository;
use Domain\MarketPlace\Models\MerchantService;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Category extends Component
{
    use LivewireAlert;
    public $menu, $menu_id, $categorySelected = null;
    public $categories = [];
    public $merchantId;
    public $listeners = ['setCategoryItems', 'refreshCategories'];
    public function mount(int $merchantId, MerchantServiceRepository $merchantServiceRepository)
    {
        $this->merchantId = $merchantId;
        $this->menu = $merchantServiceRepository->first($this->merchantId);
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
            if ($this->menu->categories()->first())
            {
                $this->categorySelected = $this->menu->categories()->first()?->id;
            }else{
                $this->categorySelected = null;
            }
            $this->categories = $this->menu->categories;
        }
    }
    public function render()
    {
        return view('admin::livewire.merchant.tabs.menu.component.category.grid.category', [
            'menu_id' => $this->menu_id
        ]);
    }
}
