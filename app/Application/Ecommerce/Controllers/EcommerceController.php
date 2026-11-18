<?php

namespace Application\Ecommerce\Controllers;

use App\Domain\AppBase\Actions\Home\FetchHomeDataAction;
use App\Domain\MarketPlace\Models\Favorite;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class EcommerceController extends Controller
{
    public function index(FetchHomeDataAction $fetchHomeDataAction)
    {
        $data = $fetchHomeDataAction->handle();

        $currentLocale = app()->getLocale();
        $isRTL = in_array($currentLocale, ['ar', 'he']);
        $favoriteIds = Auth::check() ? Favorite::where('user_id', Auth::id())->pluck('item_id')->flip()->all() : [];

        return view('ecommerce::ecommerce', array_merge($data, compact('currentLocale', 'isRTL', 'favoriteIds')));
    }

    public function staticIndex(FetchHomeDataAction $fetchHomeDataAction)
    {
        $data = $fetchHomeDataAction->handle();
        $currentLocale = app()->getLocale();
        $isRTL = in_array($currentLocale, ['ar', 'he']);
        $favoriteIds = Auth::check() ? Favorite::where('user_id', Auth::id())->pluck('item_id')->flip()->all() : [];

        return view('ecommerce::ecommerce', array_merge($data, compact('currentLocale', 'isRTL', 'favoriteIds')));
    }

    public function getNewArrivals(FetchHomeDataAction $fetchHomeDataAction)
    {
        return $this->index($fetchHomeDataAction);
    }

    public function featuredProducts(FetchHomeDataAction $fetchHomeDataAction)
    {
        return $this->index($fetchHomeDataAction);
    }

    public function getBestSellers(FetchHomeDataAction $fetchHomeDataAction)
    {
        return $this->index($fetchHomeDataAction);
    }

    public function productCollection(string $section, FetchHomeDataAction $fetchHomeDataAction)
    {
        $sectionTitles = [
            'best-sellers' => 'best_sellers',
            'featured-products' => 'featured_products',
            'new-products' => 'new_products',
            'exclusive-products' => 'exclusive_collections',
        ];

        abort_unless(isset($sectionTitles[$section]), 404);

        $allProducts = $fetchHomeDataAction->getProductCollection($section);
        $perPage = 24;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $products = new LengthAwarePaginator(
            $allProducts->forPage($page, $perPage)->values(),
            $allProducts->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => request()->query()]
        );

        $currentLocale = app()->getLocale();
        $isRTL = in_array($currentLocale, ['ar', 'he'], true);
        $sectionTitle = __('ecommerce::ecommerce.' . $sectionTitles[$section]);

        return view('ecommerce::pages.product-collection', compact(
            'currentLocale',
            'isRTL',
            'section',
            'sectionTitle',
            'products'
        ));
    }
}
