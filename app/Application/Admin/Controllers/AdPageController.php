<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdPageController extends Controller
{
    public function index(): View
    {
        return view('admin::pages.ad-management.pages.index');
    }
}
