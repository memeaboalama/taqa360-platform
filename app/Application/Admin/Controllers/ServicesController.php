<?php

namespace App\Application\Admin\Controllers;

use App\Domain\MarketPlace\Actions\Dashboard\Service\FetchSingleServiceAction;
use App\Http\Controllers\Controller;

class ServicesController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::pages.services.index');
    }

    public function showMenu($serviceId, FetchSingleServiceAction $fetchSingleServiceAction)
    {
        $menu = $fetchSingleServiceAction
            ->getById($serviceId);
        if ($menu)
            return view('admin::pages.services.show', get_defined_vars());
        else
            abort(404);
    }
}
