<?php

namespace Application\Company\Controllers;

use App\Http\Controllers\Controller;


class AuthController extends Controller
{
    public function getLoginPage()
    {
         return view('company::pages.auth.login');
    }
}
