<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;

class CityController extends Controller
{
    public function index()
    {
        return view('admin::pages.cities.index');
    }
}
