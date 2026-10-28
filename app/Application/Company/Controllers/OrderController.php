<?php

namespace Application\Company\Controllers;

use App\Domain\Provider\Actions\Dashboard\Order\FetchSingleOrderAction;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        return view('company::pages.orders.index');
    }

    public function showOrder($orderId, FetchSingleOrderAction $fetchSingleOrderAction)
    {
        $order = $fetchSingleOrderAction
            ->handle($orderId, auth('company')->user()->id);
        $towingOrderDetails  = $order->provider_towing_order_detail;
        $provider_sub_services  = $order->provider_sub_services;
        $provider  = $order->provider;
        if ($order)
            return view('company::pages.orders.show', get_defined_vars());
        else
            abort(404);
    }
}

