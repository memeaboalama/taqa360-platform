<?php
namespace Application\Marketplace\Livewire\Product\Table;

use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\Item;
use Domain\MarketPlace\Models\Merchant;
use Domain\MarketPlace\Models\Catalog\Category;
use Domain\MarketPlace\Models\Catalog\Subcategory;
use Domain\MarketPlace\Models\Catalog\ChildCategory;
use Illuminate\Support\Collection;
use Livewire\Component;

class Filter extends Component
{
    public $status = null;
    public $merchant_id = null;
    public $merchant_name = null;
    public $category_id = null;
    public $category_name = null;
    public $subcategory_id = null;
    public $subcategory_name = null;
    public $child_category_id = null;
    public $child_category_name = null;
    public $item_id = null;
    public $item_name = null;

    public Collection $statuses;
    public Collection $categories;
    public Collection $subcategories;
    public Collection $childCategories;

    public array $filters = [];

    protected $listeners = [
        'refreshProductsFilter',
        'removeMerchantFilter',
        'removeStatusFilter',
        'removeCategoryFilter',
        'removeSubcategoryFilter',
        'removeChildCategoryFilter',
        'removeItemFilter',
        'selectMerchant',
        'selectItem'
    ];

    public function booted()
    {
        $this->statuses = collect(StatusEnum::cases());
        $this->categories = Category::where('is_active', true)->get();
        $this->subcategories = Subcategory::where('is_active', true)->get();
        $this->childCategories = ChildCategory::where('is_active', true)->get();
    }

    public function refreshProductsFilter($type, $id, $name): void
    {
        switch ($type) {
            case 'merchant':
                $this->merchant_id = $id;
                $this->merchant_name = $name;
                $this->filters['merchant_id'] = $id;
                break;
            case 'category':
                $this->category_id = $id;
                $this->category_name = $name;
                $this->filters['main_category_id'] = $id;
                break;
            case 'subcategory':
                $this->subcategory_id = $id;
                $this->subcategory_name = $name;
                $this->filters['sub_category_id'] = $id;
                break;
            case 'child_category':
                $this->child_category_id = $id;
                $this->child_category_name = $name;
                $this->filters['child_category_id'] = $id;
                break;
            case 'item':
                $this->item_id = $id;
                $this->item_name = $name;
                $this->filters['item_id'] = $id;
                break;
        }

        $this->emit('refreshProducts', $this->filters);
    }

    public function selectItem($itemId, $itemName): void
    {
        $this->item_id = $itemId;
        $this->item_name = $itemName;
        $this->filters['item_id'] = $itemId;
        $this->emit('refreshProducts', $this->filters);
    }

    public function removeItemFilter(): void
    {
        $this->item_id = null;
        $this->item_name = null;
        $this->filters['item_id'] = null;
        $this->emit('refreshProducts', $this->filters);
    }

    public function removeMerchantFilter(): void
    {
        $this->merchant_id = null;
        $this->merchant_name = null;
        $this->filters['merchant_id'] = null;
        $this->emit('refreshProducts', $this->filters);
    }

    public function removeCategoryFilter(): void
    {
        $this->category_id = null;
        $this->category_name = null;
        $this->filters['main_category_id'] = null;
        $this->emit('refreshProducts', $this->filters);
    }

    public function removeSubcategoryFilter(): void
    {
        $this->subcategory_id = null;
        $this->subcategory_name = null;
        $this->filters['sub_category_id'] = null;
        $this->emit('refreshProducts', $this->filters);
    }

    public function removeChildCategoryFilter(): void
    {
        $this->child_category_id = null;
        $this->child_category_name = null;
        $this->filters['child_category_id'] = null;
        $this->emit('refreshProducts', $this->filters);
    }

    public function filterStatus(): void
    {
        $this->filters['status'] = $this->status;
        $this->emit('refreshProducts', $this->filters);
    }

    public function removeStatusFilter(): void
    {
        $this->status = null;
        $this->filters['status'] = null;
        $this->emit('refreshProducts', $this->filters);
    }

    public function render()
    {
        return view('marketplace::livewire.product.table.filter');
    }
}
