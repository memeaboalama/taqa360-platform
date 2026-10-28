<?php

namespace Application\Marketplace\Controllers;

use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function getRegisterPage(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $settings = $fetchGeneralSettingsAction->handle(['auth_page_image', 'auth_page_content_en', 'auth_page_content_ar']);
         return view('marketplace::pages.auth.register', get_defined_vars());
    }

    public function register(Request $request)
    {
        return redirect()->route('marketplace:home');
    }
}
