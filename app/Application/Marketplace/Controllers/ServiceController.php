<?php

namespace Application\Marketplace\Controllers;

use App\Domain\MarketPlace\Actions\Dashboard\Service\FetchSingleServiceAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return view('marketplace::pages.services.index');
    }

    public function showService($serviceId, FetchSingleServiceAction $fetchSingleServiceAction)
    {
        $service = $fetchSingleServiceAction
            ->handle($serviceId, auth('merchant')->user()->merchant_id);

        $addon_categories = $service->addon_categories;
        if ($service)
            return view('marketplace::pages.services.show', get_defined_vars());
        else
            abort(404);
    }
}
