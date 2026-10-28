<?php

namespace Application\Admin\Controllers\Towing;

use App\Http\Controllers\Controller;

class CityController  extends Controller
{
    public function index()
    {
        return view('admin::pages.towing.cities.index');
    }
}
