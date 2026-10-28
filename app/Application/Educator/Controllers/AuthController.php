<?php

namespace Application\Educator\Controllers;

use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function getLoginPage(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $settings = $fetchGeneralSettingsAction->handle(['auth_page_image', 'auth_page_content_en', 'auth_page_content_ar']);
        return view('educator::pages.auth.login', get_defined_vars());
    }
    public function getRegisterPage(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $settings = $fetchGeneralSettingsAction->handle(['auth_page_image', 'auth_page_content_en', 'auth_page_content_ar']);
        return view('educator::pages.auth.register', get_defined_vars());
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('educator')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('educator:login');
    }
}
