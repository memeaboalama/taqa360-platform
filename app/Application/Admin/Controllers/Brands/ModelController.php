<?php

namespace Application\Admin\Controllers\Brands;

use App\Http\Controllers\Controller;

class ModelController extends Controller
{
    public function index()
    {
        return view('admin::pages.brands.models.index');
    }
}
