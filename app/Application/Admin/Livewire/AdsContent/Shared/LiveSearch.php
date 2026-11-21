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
    public $lazyLoad = false;
    public $disabled = false;
    public $page = 1;
    public $hasMore = false;
    public $loadingMore = false;

    protected $listeners = ['clearSearch'];

    public function mount($type = 'product', $emitEvent = 'itemSelected', $selectedItem = null, $merchantId = null, $lazyLoad = false, $disabled = false)
    {
        $this->searchType = $type;
        $this->emitEventName = $emitEvent;
        $this->selectedItem = $selectedItem;
        $this->merchantId = $merchantId;
        $this->lazyLoad = $lazyLoad;
        $this->disabled = $disabled;

        if ($this->lazyLoad && !$this->disabled && !$this->selectedItem) {
            $this->fetchPaginatedResults();
        }
    }

    public function updatedQuery()
    {
        if ($this->lazyLoad) {
            $this->page = 1;
            $this->results = [];
            $this->hasMore = false;

            $query = trim((string) $this->query);
            if ($this->disabled || ($query !== '' && mb_strlen($query) < 2)) {
                return;
            }

            $this->fetchPaginatedResults();
            return;
        }

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

    public function loadMore()
    {
        if (!$this->lazyLoad || $this->disabled || !$this->hasMore || $this->loadingMore) {
            return;
        }

        $this->loadingMore = true;
        try {
            $this->page++;
            $this->fetchPaginatedResults(true);
        } finally {
            $this->loadingMore = false;
        }
    }

    private function fetchPaginatedResults(bool $append = false): void
    {
        if ($this->disabled || !in_array($this->searchType, ['product', 'merchant', 'category'], true)) {
            $this->results = [];
            $this->hasMore = false;
            return;
        }

        if ($this->searchType === 'product' && !$this->merchantId) {
            $this->results = [];
            $this->hasMore = false;
            return;
        }

        $query = trim((string) $this->query);
        $offset = ($this->page - 1) * 20;

        if ($this->searchType === 'merchant') {
            $locale = app()->getLocale();
            $builder = Merchant::query()
                ->where(function ($builder) use ($locale, $query) {
                    if ($query === '') {
                        return;
                    }

                    $builder->where('company_name->' . $locale, 'like', "%{$query}%")
                        ->orWhere('company_name->en', 'like', "%{$query}%")
                        ->orWhere('company_name->ar', 'like', "%{$query}%");
                })
                ->orderBy('id');

            $items = (clone $builder)->skip($offset)->take(20)->get(['id', 'company_name']);
            $this->hasMore = (clone $builder)->skip($offset + 20)->take(1)->exists();
            $newResults = $items->map(fn($merchant) => [
                'id' => $merchant->id,
                'title' => $merchant->getTranslation('company_name', $locale),
                'type' => 'merchant'
            ])->toArray();
        } elseif ($this->searchType === 'category') {
            $locale = app()->getLocale();
            $applySearch = function ($builder) use ($query, $locale) {
                if ($query === '') {
                    return;
                }

                $builder->where(function ($builder) use ($query, $locale) {
                    $builder->where('title->' . $locale, 'like', "%{$query}%")
                        ->orWhere('title->en', 'like', "%{$query}%")
                        ->orWhere('title->ar', 'like', "%{$query}%");
                });
            };

            $mainCategories = Category::query()->orderBy('id');
            $subcategories = Subcategory::query()->orderBy('id');
            $childCategories = ChildCategory::query()
                ->with('subcategory:id,category_id')
                ->orderBy('id');

            $applySearch($mainCategories);
            $applySearch($subcategories);
            $applySearch($childCategories);

            $sources = [
                [
                    'query' => $mainCategories,
                    'map' => fn($category) => [
                        'id' => $category->id,
                        'title' => $category->getTranslation('title', $locale),
                        'type' => 'category',
                        'level' => 1,
                        'level_label' => 'Main Category',
                    ],
                ],
                [
                    'query' => $subcategories,
                    'map' => fn($category) => [
                        'id' => $category->id,
                        'title' => $category->getTranslation('title', $locale),
                        'type' => 'category',
                        'level' => 2,
                        'parent_id' => $category->category_id,
                        'level_label' => 'Subcategory',
                    ],
                ],
                [
                    'query' => $childCategories,
                    'map' => fn($category) => [
                        'id' => $category->id,
                        'title' => $category->getTranslation('title', $locale),
                        'type' => 'category',
                        'level' => 3,
                        'parent_id' => $category->subcategory_id,
                        'parent_main_id' => $category->subcategory->category_id ?? null,
                        'level_label' => 'Child Category',
                    ],
                ],
            ];

            $counts = array_map(fn($source) => (clone $source['query'])->count(), $sources);
            $totalCount = array_sum($counts);
            $remainingOffset = $offset;
            $remainingLimit = 20;
            $newResults = [];

            foreach ($sources as $index => $source) {
                if ($remainingOffset >= $counts[$index]) {
                    $remainingOffset -= $counts[$index];
                    continue;
                }

                $limit = min($remainingLimit, $counts[$index] - $remainingOffset);
                $items = (clone $source['query'])
                    ->skip($remainingOffset)
                    ->take($limit)
                    ->get();

                foreach ($items as $item) {
                    $newResults[] = $source['map']($item);
                }

                $remainingLimit -= count($items);
                $remainingOffset = 0;

                if ($remainingLimit === 0) {
                    break;
                }
            }

            $this->hasMore = $totalCount > $this->page * 20;
        } else {
            $builder = Item::query()
                ->where('merchant_id', $this->merchantId)
                ->where(function ($builder) use ($query) {
                    if ($query === '') {
                        return;
                    }

                    $builder->where('title->en', 'like', "%{$query}%")
                        ->orWhere('title->ar', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                })
                ->orderBy('id');

            $items = (clone $builder)->skip($offset)->take(20)->get(['id', 'title', 'image']);
            $this->hasMore = (clone $builder)->skip($offset + 20)->take(1)->exists();
            $newResults = $items->map(fn($item) => [
                'id' => $item->id,
                'title' => $item->getTranslation('title', app()->getLocale()),
                'image' => $item->image_path ?? '/default.jpg',
                'type' => 'product'
            ])->toArray();
        }

        $this->results = $append
            ? array_merge($this->results, $newResults)
            : $newResults;
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
            ->with('subcategory.category')
            ->limit(5)
            ->get()
            ->map(fn($cat) => [
                'id' => $cat->id,
                'title' => $cat->title,
                'type' => 'category',
                'level' => 3,
                'parent_id' => $cat->subcategory_id ?? null,
                'parent_main_id' => $cat->subcategory->category_id ?? null,
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
            $this->hasMore = false;
        }
    }

    public function clearSelection()
    {
        $this->selectedItem = null;
        $this->query = '';
        $this->results = [];
        $this->hasMore = false;
        $this->emit($this->emitEventName, null);

        if ($this->lazyLoad && !$this->disabled && $this->searchType === 'merchant') {
            $this->page = 1;
            $this->fetchPaginatedResults();
        }
    }

    public function clearSearch()
    {
        $this->query = '';
        $this->results = [];
        $this->selectedItem = null;
        $this->hasMore = false;

        if ($this->lazyLoad && !$this->disabled) {
            $this->page = 1;
            $this->fetchPaginatedResults();
        }
    }

    public function render()
    {
        return view('admin::livewire.ads-content.shared.live-search');
    }
}
