<?php

namespace Application\Admin\Livewire\AdsContent\Shared;

use Domain\MarketPlace\Models\Catalog\Category;
use Domain\MarketPlace\Models\Catalog\ChildCategory;
use Domain\MarketPlace\Models\Catalog\Subcategory;
use Domain\MarketPlace\Models\Item;
use Domain\MarketPlace\Models\Merchant;
use Livewire\Component;

class LiveSearch extends Component
{
    public $searchType;
    public $query = '';
    public $results = [];
    public $selectedItem = null;
    public $emitEventName = 'itemSelected';
    public $merchantId = null;

    protected $listeners = ['clearSearch'];

    public function mount($type = 'product', $emitEvent = 'itemSelected', $selectedItem = null, $merchantId = null)
    {
        $this->searchType = $type;
        $this->emitEventName = $emitEvent;
        $this->selectedItem = $selectedItem;
        $this->merchantId = $merchantId;
    }

    public function updatedQuery()
    {
        if (strlen($this->query) < 2) {
            $this->results = [];
            return;
        }

        $this->results = match($this->searchType) {
            'product' => $this->searchProduct(),
            'merchant' => $this->searchMerchant(),
            'category' => $this->searchCategory(),
            'subcategory' => $this->searchSubcategories(),
            'child_category' => $this->searchChildCategories(),
            default => []
        };
    }

    private function searchProduct()
    {
        return Item::when($this->merchantId, function ($q) {
                $q->where('merchant_id', $this->merchantId);
            })
            ->where(function($q) {
                $q->where('title->en', 'like', "%{$this->query}%")
                  ->orWhere('title->ar', 'like', "%{$this->query}%")
                  ->orWhere('description', 'like', "%{$this->query}%");
            })
            ->select('id', 'title', 'image')
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'title' => $item->getTranslation('title', app()->getLocale()),
                'image' => $item->image_path ?? '/default.jpg',
                'type' => 'product'
            ])
            ->toArray();
    }

    private function searchMerchant()
    {
        $locale = app()->getLocale();
        return Merchant::where(function($q) use ($locale) {
                $q->where('company_name->' . $locale, 'like', "%{$this->query}%")
                    ->orWhere('company_name->en', 'like', "%{$this->query}%")
                    ->orWhere('company_name->ar', 'like', "%{$this->query}%");
            })
            ->select('id', 'company_name')
            ->limit(10)
            ->get()
            ->map(fn($merchant) => [
                'id' => $merchant->id,
                'title' => $merchant->getTranslation('company_name', $locale),
                'image' => $merchant->logo ?? '/default.jpg',
                'type' => 'merchant'
            ])
            ->toArray();
    }

    private function searchCategory()
    {
        $query = $this->query;
        $results = [];

        $mainCategories = Category::where('title->ar', 'like', "%{$query}%")
            ->orWhere('title->en', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn($cat) => [
                'id' => $cat->id,
                'title' => $cat->title,
                'type' => 'category',
                'level' => 1,
                'parent_id' => null,
                'slug' => $cat->slug ?? null
            ]);

        $subCategories = Subcategory::where('title->ar', 'like', "%{$query}%")
            ->orWhere('title->en', 'like', "%{$query}%")
            ->with('category')
            ->limit(5)
            ->get()
            ->map(fn($cat) => [
                'id' => $cat->id,
                'title' => $cat->title,
                'type' => 'category',
                'level' => 2,
                'parent_id' => $cat->category_id ?? null,
                'slug' => $cat->slug ?? null
            ]);

        $childCategories = ChildCategory::where('title->ar', 'like', "%{$query}%")
            ->orWhere('title->en', 'like', "%{$query}%")
            ->with('subcategory')
            ->limit(5)
            ->get()
            ->map(fn($cat) => [
                'id' => $cat->id,
                'title' => $cat->title,
                'type' => 'category',
                'level' => 3,
                'parent_id' => $cat->subcategory_id ?? null,
                'slug' => $cat->slug ?? null
            ]);

        return $mainCategories->concat($subCategories)->concat($childCategories)->take(15)->toArray();
    }

    private function searchSubcategories()
    {
        $locale = app()->getLocale();
        return Subcategory::where(function($q) use ($locale) {
                $q->where('title->' . $locale, 'like', "%{$this->query}%")
                    ->orWhere('title->en', 'like', "%{$this->query}%")
                    ->orWhere('title->ar', 'like', "%{$this->query}%");
            })
            ->select('id', 'title', 'slug', 'image')
            ->limit(10)
            ->get()
            ->map(fn($cat) => [
                'id' => $cat->id,
                'title' => $cat->getTranslation('title', app()->getLocale()),
                'slug' => $cat->slug,
                'image' => $cat->image ?? '/default.jpg',
                'type' => 'subcategory'
            ])
            ->toArray();
    }

    private function searchChildCategories()
    {
        $locale = app()->getLocale();
        return ChildCategory::where(function($q) use ($locale) {
                $q->where('title->' . $locale, 'like', "%{$this->query}%")
                    ->orWhere('title->en', 'like', "%{$this->query}%")
                    ->orWhere('title->ar', 'like', "%{$this->query}%");
            })
            ->select('id', 'title', 'slug', 'image')
            ->limit(10)
            ->get()
            ->map(fn($cat) => [
                'id' => $cat->id,
                'title' => $cat->getTranslation('title', app()->getLocale()),
                'slug' => $cat->slug,
                'image' => $cat->image ?? '/default.jpg',
                'type' => 'child_category'
            ])
            ->toArray();
    }

    public function select($index)
    {
        $this->selectedItem = $this->results[$index] ?? null;

        if ($this->selectedItem) {
            $this->emit($this->emitEventName, $this->selectedItem);
            $this->query = '';
            $this->results = [];
        }
    }

    public function clearSelection()
    {
        $this->selectedItem = null;
        $this->emit($this->emitEventName, null);
    }

    public function clearSearch()
    {
        $this->query = '';
        $this->results = [];
        $this->selectedItem = null;
    }

    public function render()
    {
        return view('admin::livewire.ads-content.shared.live-search');
    }
}
