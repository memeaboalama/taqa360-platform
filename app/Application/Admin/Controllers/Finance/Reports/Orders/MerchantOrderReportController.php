<?php

namespace Application\Admin\Controllers\Finance\Reports\Orders;

use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Http\Controllers\Controller;

class MerchantOrderReportController extends Controller
{
    public function index()
    {
        return view('admin::pages.finance.reports.orders.merchant.index');
    }
}
