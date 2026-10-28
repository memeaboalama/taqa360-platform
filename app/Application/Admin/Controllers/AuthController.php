<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {

        return view('admin::pages.auth.login');
    }
}
