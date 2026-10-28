<?php

namespace Application\Ecommerce\Controllers;

use App\Domain\AppBase\Actions\Home\FetchHomeDataAction;
use App\Domain\MarketPlace\Models\Favorite;
use App\Http\Controllers\Controller;
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
}
