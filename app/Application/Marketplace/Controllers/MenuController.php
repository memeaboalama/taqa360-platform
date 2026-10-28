<?php

namespace Application\Marketplace\Controllers;

use App\Domain\MarketPlace\Actions\Dashboard\Service\FetchSingleServiceAction;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function index()
    {
        return view('marketplace::pages.menu.index');
    }

    public function showMenu($serviceId, FetchSingleServiceAction $fetchSingleServiceAction)
    {
        $menu = $fetchSingleServiceAction
            ->handle($serviceId, auth('merchant')->user()->merchant_id);
        if ($menu)
            return view('marketplace::pages.menu.show', get_defined_vars());
        else
            abort(404);
    }
}
