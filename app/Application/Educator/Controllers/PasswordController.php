<?php

namespace Application\Educator\Controllers;

use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    public $settings;
    public function __construct(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $this->settings = $fetchGeneralSettingsAction->handle(['auth_page_image', 'auth_page_content_en', 'auth_page_content_ar']);
    }

    public function forgetPassword()
    {
        $settings =$this->settings;
        return view('educator::pages.auth.password.forget', get_defined_vars());
    }

    public function verifyOTP()
    {
        $settings =$this->settings;
        return view('educator::pages.auth.password.verify', get_defined_vars());
    }

    public function resetPassword()
    {
        $settings =$this->settings;
        return view('educator::pages.auth.password.reset', get_defined_vars());
    }
}
