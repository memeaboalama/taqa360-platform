<?php

namespace Application\Marketplace\Controllers;

use App\Domain\MarketPlace\Actions\Dashboard\Employee\FetchEmployessAction;
use App\Domain\MarketPlace\Actions\Dashboard\Home\StatsAction;
use App\Domain\MarketPlace\Actions\Dashboard\Order\FetchOrdersAction;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class MerchantController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(StatsAction $statsAction, FetchOrdersAction $fetchOrdersAction, FetchEmployessAction $fetchEmployeesAction)
    {
         if(!resolve('_InitialMerchant'))
         return redirect(RouteServiceProvider::MARKETPLACE_LOGIN);
         $merchantId = resolve('_InitialMerchant');
         $stats = $statsAction->handle($merchantId);
         $latestOrders = $fetchOrdersAction->handle($merchantId);
         $topEmployees = $fetchEmployeesAction->getTop(5);
         return view('marketplace::pages.home', get_defined_vars());
    }

    public function docs()
    {
        return view('marketplace::pages.documentation.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
