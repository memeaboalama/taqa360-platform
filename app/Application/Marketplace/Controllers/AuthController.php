<?php

namespace Application\Marketplace\Controllers;

use App\Domain\MarketPlace\Actions\Dashboard\Auth\Employee\LoginEmployeeAction;
use App\Domain\MarketPlace\Actions\Dashboard\Auth\LoginAction;
use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Domain\Otp\Actions\SendOtpAction;
use App\Http\Controllers\Controller;
use App\Mail\Marketplace\ConfirmMail;
use App\Providers\RouteServiceProvider;
use App\Support\Enum\OTPTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AuthController extends Controller
{
    public function getLoginPage(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $settings = $fetchGeneralSettingsAction->handle(['auth_page_image', 'auth_page_content_en', 'auth_page_content_ar']);
         return view('marketplace::pages.auth.login', get_defined_vars());
    }

    public function login(Request $request, LoginAction $loginAction, LoginEmployeeAction $loginEmployeeAction)
    {
        $data = $request->validate([
            'email' => 'required|email|min:3',
            'password' => 'required|string|min:4',
        ]);

        $authenticated = $loginAction->handle($data) ?: $loginEmployeeAction->handle($data);

        if (!$authenticated) {
            return back()
                ->withErrors(['email' => "this credential doesn't match our records"])
                ->withInput($request->only('email'));
        }

        $user = auth('merchant')->user() ?? auth('merchant_employee')->user();
        session()->flash('login_welcome', __('marketplace::home.welcome_back_msg', [
            'name' => $user->user_name ?? $user->name ?? $data['email'],
        ]));

        return redirect()->route('marketplace:home');
    }

}
