<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    public function forgetPassword()
    {
        return view('admin::pages.auth.password.forget', get_defined_vars());
    }

    public function verifyOTP()
    {
        return view('admin::pages.auth.password.verify', get_defined_vars());
    }

    public function resetPassword()
    {
        return view('admin::pages.auth.password.reset', get_defined_vars());
    }
}
