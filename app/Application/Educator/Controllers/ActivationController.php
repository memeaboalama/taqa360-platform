<?php

namespace Application\Educator\Controllers;

use App\Http\Controllers\Controller;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ActivationController extends Controller
{
    use LivewireAlert;
    public function getProfile()
    {
        return view('educator::pages.profile.index');
    }

    public function getHomePage()
    {
        return view('educator::pages.home.index');
    }

    public function forgetPassword()
    {
        return view('educator::pages.profile.password');
    }

    public function verifyEmail()
    {
        return view('educator::pages.profile.verify.email');
    }

}
