<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;

class SpecificationController extends Controller
{
    public function index()
    {
        return view('admin::pages.specifications.index');
    }
}
