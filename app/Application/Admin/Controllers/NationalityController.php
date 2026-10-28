<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;

class NationalityController extends Controller
{
    public function index()
    {
        return view('admin::pages.nationalities.index');
    }
}
