<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    public function index()
    {
        return view('admin::pages.promocodes.index');
    }

    public function selectmerchants(int $id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::pages.promocodes.selectmerchants', get_defined_vars());
    }
}
