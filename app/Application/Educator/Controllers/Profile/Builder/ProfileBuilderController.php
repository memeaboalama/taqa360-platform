<?php

namespace Application\Educator\Controllers\Profile\Builder;

use App\Http\Controllers\Controller;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ProfileBuilderController extends Controller
{
    use LivewireAlert;
    public function getProfileBuilder()
    {
        return view('educator::pages.profile.builder.builder');
    }
    public function getProfile()
    {
        return view('educator::pages.profile.index');
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
