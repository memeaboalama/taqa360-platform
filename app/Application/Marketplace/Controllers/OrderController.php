<?php

namespace Application\Marketplace\Controllers;

use App\Domain\MarketPlace\Actions\Dashboard\Order\FetchSingleOrderAction;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('marketplace::pages.orders.index');
    }

    public function showOrder($orderId, FetchSingleOrderAction $fetchSingleOrderAction)
    {
        $order = $fetchSingleOrderAction
            ->handle($orderId, resolve('_InitialMerchant'));
        if ($order && auth('merchant_employee')->check()) {
            $isAuthorizedBranch = MerchantOrder::query()
                ->forMerchantEmployee(auth('merchant_employee')->user())
                ->whereKey($order->id)
                ->exists();
            abort_unless($isAuthorizedBranch, 404);
        }
        if ($order)
        {
            $orderServices = $order->services;
            $orderUser = $order->user;
            $address = $order->address;
            return view('marketplace::pages.orders.show', get_defined_vars());
        }else{
            abort(404);
        }
    }
}
