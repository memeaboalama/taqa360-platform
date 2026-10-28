<?php

namespace Application\Marketplace\Controllers;

use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    public function index()
    {
        return view('marketplace::pages.promocodes.index');
    }
}
