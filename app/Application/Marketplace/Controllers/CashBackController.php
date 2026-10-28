<?php

namespace Application\Marketplace\Controllers;

use App\Http\Controllers\Controller;

class CashBackController extends Controller
{
    public function index()
    {
        return view('marketplace::pages.cashbacks.index');
    }
}
