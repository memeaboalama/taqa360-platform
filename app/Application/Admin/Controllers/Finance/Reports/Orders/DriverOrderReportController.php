<?php

namespace Application\Admin\Controllers\Finance\Reports\Orders;

use App\Http\Controllers\Controller;

class DriverOrderReportController extends Controller
{
    public function index()
    {
        return view('admin::pages.finance.reports.orders.driver.index');
    }
}
