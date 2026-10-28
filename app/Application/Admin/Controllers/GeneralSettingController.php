<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;

class GeneralSettingController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::pages.settings.index');
    }
}
