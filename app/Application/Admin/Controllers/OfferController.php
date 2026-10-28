<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;

class OfferController extends Controller
{
    public function index()
    {
        return view('admin::pages.offer.index');
    }

    public function selectmerchants(int $id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::pages.offer.selectmerchants', get_defined_vars());
    }
}
