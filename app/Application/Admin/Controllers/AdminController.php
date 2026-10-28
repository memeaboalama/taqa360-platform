<?php

namespace Application\Admin\Controllers;

use App\Domain\Admin\Actions\Dashboard\Home\StatsAction;
use App\Domain\Admin\Actions\Dashboard\Merchant\FetchTopSalesMerchantsAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(StatsAction $statsAction, FetchTopSalesMerchantsAction $fetchTopSalesMerchantsAction)
    {
        $stats = $statsAction->handle();
        $ordersByMonth = json_encode($stats['ordersByMonth']);
        $salesByMonth = json_encode($stats['ordersByMonth']);
        $bestSellers =[];
        //  $fetchTopSalesMerchantsAction->getTop(10);
        return view('admin::pages.home', get_defined_vars());
    }

}
