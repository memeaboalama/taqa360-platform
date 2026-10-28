<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;

class BannerController extends Controller
{
    public function index()
    {
        return view('admin::pages.banners.index');
    }
}
