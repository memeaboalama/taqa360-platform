<?php

namespace App\Application\Admin\Controllers\Merchant;

use App\Http\Controllers\Controller;

class MerchantCashBackController extends Controller
{
    public function index()
    {
        return view('admin::pages.cashbacks.merchants.index');
    }

    public function selectmerchants(int $id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::pages.cashbacks.selectmerchants', get_defined_vars());
    }
}
