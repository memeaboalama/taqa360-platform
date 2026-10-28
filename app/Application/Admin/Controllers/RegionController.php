<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;

class RegionController extends Controller
{
    public function index()
    {
        return view('admin::pages.regions.index');
    }
}