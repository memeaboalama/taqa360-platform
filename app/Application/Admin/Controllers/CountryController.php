<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    public function index()
    {
        return view('admin::pages.countries.index');
    }
}