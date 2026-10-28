<?php

namespace Application\Company\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function getRegisterPage()
    {
         return view('company::pages.auth.register');
    }

    public function register(Request $request)
    {
        return redirect()->route('company:home');
    }
}
