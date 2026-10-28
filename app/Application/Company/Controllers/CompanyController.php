<?php

namespace Application\Company\Controllers;

use App\Domain\Provider\Actions\Dashboard\Home\StatsAction;
use App\Domain\Provider\Actions\Dashboard\Order\FetchOrdersAction;
use App\Domain\Provider\Actions\Dashboard\Provider\FetchProviderAction;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    public function index(StatsAction $statsAction)
    {
        $stats = $statsAction->handle(resolve('_InitialCompany'));
        return view('company::pages.home', get_defined_vars());
    }

    public function docs()
    {
        return view('company::pages.documentation.index');
    }
}
