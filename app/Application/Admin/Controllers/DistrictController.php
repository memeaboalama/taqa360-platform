<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;

class DistrictController extends Controller
{
    public function index()
    {
        return view('admin::pages.districts.index');
    }
}
