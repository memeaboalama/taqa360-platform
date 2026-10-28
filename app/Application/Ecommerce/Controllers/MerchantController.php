<?php

namespace App\Application\Ecommerce\Controllers;

use App\Domain\Ecommerce\Actions\Merchant\FetchMerchantCategoryAction;
use App\Domain\Ecommerce\Actions\Merchant\FetchMerchantItemAction;
use App\Domain\MarketPlace\Models\Favorite;
use App\Http\Controllers\Controller;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerchantController extends Controller
{
    public function __construct(
        protected FetchMerchantItemAction $filterItemsAction,
        protected FetchMerchantCategoryAction $fetchMerchantCategoryAction
    ){}

    public function index(Request $request)
    {
        $query = Merchant::query()
            ->where('status', 'active')
            ->whereHas('items', fn($q) => $q->where('status', 'active'));

        if ($request->has('category') && $request->category != 'all') {
             $query->whereHas('items', function ($q) use ($request) {
                $q->where('status', 'active')
                    ->where('main_category_id', $request->category);
            });
        }

        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('company_name', 'like', '%' . $request->q . '%');
            });
        }

        switch ($request->get('sort_by')) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
             case 'top_rated':
                 break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $merchants = $query->paginate($request->get('per_page', 12))->withQueryString();



        $categories = \Domain\MarketPlace\Models\Catalog\Category::whereHas('items.merchant', function($q) {
            $q->where('status', 'active');
        })->get();


        if ($request->has('header_request')) {
             return view('ecommerce::pages.merchant.partials.list_result', compact('merchants'));
        }

        return view('ecommerce::pages.merchant.list', compact('merchants', 'categories'));
    }

    public function staticIndex(Request $request)
    {
        $query = Merchant::query()
            ->where('status', 'active')
            ->whereHas('items', fn($q) => $q->where('status', 'active'));

        if ($request->has('category') && $request->category != 'all') {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('status', 'active')
                    ->where('main_category_id', $request->category);
            });
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('company_name', 'like', '%' . $request->q . '%');
            });
        }

        match ($request->get('sort_by')) {
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            default => $query->latest(),
        };

        $merchants = $query->paginate($request->get('per_page', 12))->withQueryString();
        $categories = \Domain\MarketPlace\Models\Catalog\Category::whereHas('items.merchant', function ($q) {
            $q->where('status', 'active');
        })->get();

        return view('ecommerce::pages.merchant.merchants', compact('merchants', 'categories'));
    }

    public function show(Merchant $merchant, Request $request)
    {
        return $this->handleRequest($merchant, $request);
    }

    public function filter(Merchant $merchant, Request $request)
    {
        return $this->handleRequest($merchant, $request, true);
    }

    private function handleRequest(Merchant $merchant, Request $request, $isFilterRequest = false)
    {
        if ($merchant->status !== 'active' || !$merchant->items()->where('status', 'active')->exists()) {
            abort(404);
        }

        $categories = $this->fetchMerchantCategoryAction->handle($merchant->id);
        $categoryCounts = $this->filterItemsAction->getCategoryCounts($merchant->id, $categories);

        // Read-only: the store's own uploaded files, reused from the existing
        // files() relation. Logos stay out of the list because the hero
        // already shows them.
        $merchant->loadMissing(['files' => fn ($q) => $q->where('collection_name', '!=', 'app_logo')]);
        $attachments = $merchant->files->sortByDesc(fn ($f) => $f->order_column ?? 0)->values();

        $filters = [
            'paginated' => true,
            'per_page' => $request->get('per_page', 12),
            'sort_by' => $request->get('sort_by', 'latest'),
            'page' => $request->get('page', 1),
            'category' => $request->get('category', 'all'),
            'q' => trim((string) $request->get('q', '')),
        ];

        $products = $this->filterItemsAction->handle($filters, $merchant->id);

        $data = compact('merchant', 'products', 'categories', 'categoryCounts', 'attachments');

        if ($isFilterRequest && $request->expectsJson()) {
            $newUrl = route('merchant.show', array_merge(['merchant' => $merchant->id], $request->query()));

            return response()->json([
                'product_grid_html' => view('ecommerce::pages.partials.product-grid', compact('products'))->render(),
                'new_url' => $newUrl,
            ]);
        }

        $data['favoriteIds'] = Auth::check()
            ? Favorite::where('user_id', Auth::id())->pluck('item_id')->flip()->all()
            : [];

        return view('ecommerce::pages.merchant.merchant', $data);
    }
}
