<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;

class WalletController extends Controller
{
    public function users()
    {
        return view('admin::pages.wallet.users.index');
    }
    public function history(int $id)
    {
        return view('admin::pages.wallet.users.history.index',get_defined_vars());
    }
    public function walletCoupons()
    {
        return view('admin::pages.wallet.index');
    }

    public function selectmerchants(int $id)
    {
        return view('admin::pages.wallet.selectmerchants', get_defined_vars());
    }



}
