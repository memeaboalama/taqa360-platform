<?php

namespace Application\Marketplace\Controllers\Finance\Reports\Orders;

use App\Http\Controllers\Controller;

class OrderReportController extends Controller
{
    public function index()
    {
        return view('marketplace::pages.finance.reports.orders.index');
    }
}
