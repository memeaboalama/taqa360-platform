<?php

namespace Application\Admin\Controllers\Brands;

use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function index()
    {
        return view('admin::pages.brands.brands.index');
    }
}
