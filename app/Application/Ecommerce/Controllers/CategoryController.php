<?php

namespace App\Application\Ecommerce\Controllers;

use App\Domain\MarketPlace\Models\Favorite;
use App\Http\Controllers\Controller;
use Domain\MarketPlace\Models\Catalog\Category;
use Domain\MarketPlace\Models\Catalog\Subcategory;
use Domain\MarketPlace\Models\Catalog\ChildCategory;
use Domain\MarketPlace\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function search(Request $request)
    {
        if ($request->has('category') && $request->category != 'all') {
            $request->merge(['main_category' => $request->category]);
        }
        return $this->index($request);
    }

    public function show(Request $request, $id)
    {
        $request->merge(['main_category' => $id]);
        return $this->index($request);
    }

    public function index(Request $request)
    {
        $filters = $this->resolveFilters($request);
        $metaData = $this->getCategoryMetaData($filters);
        $products = $this->getFilteredProducts($request, $filters);

        $viewData = array_merge($filters, $metaData, ['products' => $products]);

        return view('ecommerce::pages.categories-legacy', $viewData);
    }

    public function staticIndex(Request $request)
    {
        $filters = $this->resolveFilters($request);
        $metaData = $this->getCategoryMetaData($filters);
        $products = $this->getFilteredProducts($request, $filters);
        $favoriteIds = Auth::check() ? Favorite::where('user_id', Auth::id())->pluck('item_id')->flip()->all() : [];

        return view('ecommerce::pages.categories', array_merge($filters, $metaData, [
            'products' => $products,
            'favoriteIds' => $favoriteIds,
        ]));
    }

    private function resolveFilters(Request $request)
    {
        $mainId  = ($request->get('main_category') === 'all') ? null : $request->get('main_category');
        $subId   = ($request->get('sub_category') === 'all') ? null : $request->get('sub_category');
        $childId = ($request->get('child_category') === 'all') ? null : $request->get('child_category');

        return [
            'activeMainId'  => $mainId,
            'activeSubId'   => $subId,
            'activeChildId' => $childId,
        ];
    }

    private function getCategoryMetaData($filters)
    {
        $activeMainId = $filters['activeMainId'];
        $activeSubId = $filters['activeSubId'];
        $activeChildId = $filters['activeChildId'];

        $currentCategory = $activeMainId ? Category::find($activeMainId) : null;

        if ($activeMainId) {
            $subCategories = Subcategory::where('category_id', $activeMainId)
                ->where('is_active', true)
                ->withCount('items')
                ->get();
        } else {
            $subCategories = Subcategory::where('is_active', true)
                ->withCount('items')
                ->orderBy('items_count', 'desc')
                ->take(24)
                ->get();
        }

        $childCategories = collect();

        if ($activeSubId) {
            $childCategories = ChildCategory::where('subcategory_id', $activeSubId)
                ->where('is_active', true)
                ->withCount('items')
                ->get();

        } elseif ($activeMainId) {
             $childCategories = ChildCategory::whereHas('subCategory', function($q) use ($activeMainId) {
                    $q->where('category_id', $activeMainId);
                })
                ->where('is_active', true)
                ->withCount('items')
                ->limit(60)
                ->get();

        } else {
            $selectedChild = null;
            if ($activeChildId) {
                $selectedChild = ChildCategory::where('id', $activeChildId)
                    ->withCount('items')
                    ->first();
            }

            $query = ChildCategory::where('is_active', true)->withCount('items');

            if ($activeChildId) {
                $query->where('id', '!=', $activeChildId);
            }

            $randomChildren = $query->inRandomOrder()
                ->limit(100)
                ->get()
                ->unique('subcategory_id')
                ->take(20);

            $childCategories = $randomChildren;

            if ($selectedChild) {
                $childCategories->prepend($selectedChild);
            }
        }

        return compact('currentCategory', 'subCategories', 'childCategories');
    }

    private function getFilteredProducts(Request $request, $filters)
    {
        $query = Item::where('status', 'active')->with(['merchant']);

        if ($filters['activeMainId']) $query->where('main_category_id', $filters['activeMainId']);
        if ($filters['activeSubId']) $query->where('sub_category_id', $filters['activeSubId']);
        if ($filters['activeChildId']) $query->where('child_category_id', $filters['activeChildId']);

        if ($request->has('brands')) $query->whereIn('brand_id', (array)$request->brands);
        if ($request->has('price_ranges')) $this->applyPriceScope($query, (array)$request->price_ranges);

        if ($request->filled('q') && $request->q != 'null') {
            $term = $request->q;
            $query->where(function($q) use ($term) {
                $q->where('title', 'like', '%' . $term . '%')
                  ->orWhere('description', 'like', '%' . $term . '%');
            });
        }

        $sortBy = match($request->get('sort_by')) {
            'price_asc', 'price_desc' => 'default_price',
            'title' => 'title',
            default => 'created_at'
        };

        $sortDir = ($request->get('sort_by') === 'price_asc' || $request->get('sort_by') === 'title') ? 'asc' : 'desc';

        return $query->orderBy($sortBy, $sortDir)
            ->paginate($request->get('per_page', 16))
            ->withQueryString();
    }

    private function applyPriceScope($query, $ranges)
    {
        $query->where(function($q) use ($ranges) {
            foreach ($ranges as $range) {
                if ($range == 'hundred') $q->orWhereBetween('default_price', [10, 100]);
                elseif ($range == 'twohundred') $q->orWhereBetween('default_price', [100, 200]);
                elseif ($range == 'threehundred') $q->orWhereBetween('default_price', [200, 300]);
                elseif ($range == 'fourhundred') $q->orWhereBetween('default_price', [300, 400]);
                elseif ($range == 'fourhundredabove') $q->orWhere('default_price', '>=', 400);
            }
        });
    }
}
