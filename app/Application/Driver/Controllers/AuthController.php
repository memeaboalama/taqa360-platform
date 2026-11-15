<?php

namespace Application\Driver\Controllers;

use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Domain\Otp\Actions\SendOtpAction;
use App\Domain\Driver\Actions\Auth\LoginAction;

use App\Http\Controllers\Controller;
use App\Mail\Driver\ConfirmMail;
use App\Providers\RouteServiceProvider;
use App\Support\Enum\OTPTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AuthController extends Controller {
    public function getLoginPage( FetchGeneralSettingsAction $fetchGeneralSettingsAction ) {
        $settings = $fetchGeneralSettingsAction->handle( [ 'auth_page_image', 'auth_page_content_en', 'auth_page_content_ar' ] );
        return view( 'driver::pages.auth.login', get_defined_vars() );
    }

    public function login( Request $request, LoginAction $loginAction ) {
        $data = $request->validate( [
            'email' => 'required|email|min:3',
            'password' => 'required|string|min:4',
        ] );

        if ( !$loginAction->handle( $data ) ) {
            return back()
            ->withErrors( [ 'email' => "this credential doesn't match our records" ] )
            ->withInput( $request->only( 'email' ) );
        }

        $user = auth( 'driver' )->user();
        session()->flash( 'login_welcome', __( 'driver::home.welcome_back_msg', [
            'name' => $user->name ?? $data[ 'email' ],
        ] ) );

        return redirect()->route( 'driver:home' );
    }
}
