<?php

namespace Application\Marketplace\Controllers;

use App\Domain\MarketPlace\Actions\Api\Branch\FetchMerchantBranchAction;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\FetchSingleBranchAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
         return view('marketplace::pages.branches.index');
    }

    public function show($id, FetchSingleBranchAction $fetchSingleBranchAction): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $branch = $fetchSingleBranchAction
            ->handle($id, resolve('_InitialMerchant'));

        if ($branch)
            return view('marketplace::pages.branches.show',get_defined_vars());
        else
            abort(404);

    }
}
