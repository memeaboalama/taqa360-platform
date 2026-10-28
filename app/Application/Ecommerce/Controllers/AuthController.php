<?php

namespace App\Application\Ecommerce\Controllers;

use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Domain\Ecommerce\Auth\LoginAction;
use App\Domain\Otp\Actions\CheckOtpAction;
use App\Domain\Otp\Actions\SendOtpAction;
use App\Domain\User\Actions\Auth\RegisterUserAction;
use App\Domain\User\Models\User;
use App\Http\Controllers\Controller;
use App\Support\Enum\OTPTypes;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    /**
     * Temporary switch (config/auth.php → email_verification.enabled, driven by
     * EMAIL_VERIFICATION_ENABLED in .env). When false the "confirm your email"
     * flow is suspended: no code is emailed and unverified accounts are not
     * blocked. Flip it back to true to restore the original behaviour.
     *
     * NOTE: currently unused - every call site is commented out because the whole
     * email-verification flow is disabled by commenting (see login() and register()
     * below). The method is kept so it can be re-wired when verification returns.
     */
    private function emailVerificationEnabled(): bool
    {
        return (bool) config('auth.email_verification.enabled', true);
    }

    public function getLoginPage(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        return view('ecommerce::pages.auth.login', get_defined_vars());
    }

    public function getRegisterPage(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        return view('ecommerce::pages.auth.register', get_defined_vars());
    }

    public function getVerifyPage(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $userId = session()->get('ecommerce_user_id_for_verification');
        $userEmail = session()->get('ecommerce_user_email_for_verification');
        $debugOtp = session()->get('debugOtp');

        if (!$userId || !$userEmail) {
            return redirect()->route('auth.register');
        }

        return view('ecommerce::pages.auth.verify', get_defined_vars());
    }

    public function login(Request $request, LoginAction $loginAction)
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
                'remember' => ['sometimes', 'boolean'],
            ]);

            if (!Auth::attempt([
                'email' => $validated['email'],
                'password' => $validated['password']
            ], $request->boolean('remember'))) {
                return back()
                    ->withInput($request->only('email', 'remember'))
                    ->with('error', __('ecommerce::ecommerce.auth.validation.credentials_do_not_match'));
            }

            // Enforce email verification before granting access
            $user = Auth::user();

            // ---------------------------------------------------------------------------
            // [DISABLED - email verification, comment-only] Forced redirect to /verify for
            // accounts without email_verified_at. Commented out so an unverified user can
            // sign in and keep using the site without entering a confirmation code.
            // Kept commented (not deleted): uncomment this block to re-enforce verification
            // on the legacy login route (POST /login-legacy).
            // NOTE: password / credential validation above is untouched.
            // ---------------------------------------------------------------------------
            // if ($this->emailVerificationEnabled() && !$user->email_verified_at) {
            //     Auth::guard('web')->logout();
            //     session()->put('ecommerce_user_id_for_verification', $user->id);
            //     session()->put('ecommerce_user_email_for_verification', $user->email);
            //     return redirect()->route('verify')
            //         ->with('error', __('ecommerce::ecommerce.auth.validation.email_not_verified'));
            // }

            $request->session()->regenerate();

            return redirect()->intended(route('home'))
                ->with('success', __('ecommerce::ecommerce.auth.alerts.login_success'));

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput($request->only('email', 'remember'));
        } catch (\Exception $e) {
            Log::error('Login Failed: ' . $e->getMessage());

            return back()
                ->withInput($request->only('email', 'remember'))
                ->with('error', __('ecommerce::ecommerce.auth.alerts.login_error'));
        }
    }

    public function register(Request $request, RegisterUserAction $registerAction, SendOtpAction $sendOtpAction)
    {
        try {
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'min:2', 'max:50'],
                'last_name'  => ['required', 'string', 'min:2', 'max:50'],
                'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password'   => ['required', 'string', 'min:8'],
            ]);

            $user = $registerAction->handle([
                'name' => "{$validated['first_name']} {$validated['last_name']}",
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
            ]);

            // Email verification is temporarily suspended: skip the OTP email,
            // mark the account as verified right away and sign the user in.
            // ---------------------------------------------------------------------------
            // [DISABLED - email verification, comment-only]
            // 1) The `if (! $this->emailVerificationEnabled())` condition is commented out
            //    so this branch always runs and no longer depends on the
            //    EMAIL_VERIFICATION_ENABLED environment variable.
            // 2) The OTP send + forced redirect to /verify below it is commented out too,
            //    so registration no longer emails a confirmation code or holds the user
            //    on the /verify page.
            // Nothing was deleted: to restore, uncomment `if (...) {` and its matching `}`
            // and uncomment the OTP block that follows.
            // ---------------------------------------------------------------------------
            // if (!$this->emailVerificationEnabled()) {
                // Re-fetch a clean instance: RegisterUserAction leaves a stray
                // `msgOffer` attribute in memory, and saving it would fail on the
                // (non-existent) users.msgOffer column.
                $user = User::query()->findOrFail($user->id);

                $user->forceFill([
                    'email_verified_at' => now(),
                    'status' => 'active',
                ])->save();

                Auth::guard('web')->login($user);
                $request->session()->regenerate();

                return redirect()->route('home')
                    ->with('success', __('ecommerce::ecommerce.auth.alerts.register_success'));
            // }

            // [DISABLED - email verification, comment-only] confirmation code + forced
            // redirect to the /verify page (kept commented, not deleted).
            // $code = $sendOtpAction->handleForMail($user, OTPTypes::USER_VERIFY_EMAIL, 120, throwOnFailure: true);

            // session()->put('ecommerce_user_id_for_verification', $user->id);
            // session()->put('ecommerce_user_email_for_verification', $user->email);

            // return redirect()->route('verify')
            //     ->with('success', __('ecommerce::ecommerce.auth.alerts.register_success'));

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput($request->only('first_name', 'last_name', 'email'));
        } catch (\Exception $e) {
            Log::error('User Registration Failed: ' . $e->getMessage());

            return back()
                ->withInput($request->only('first_name', 'last_name', 'email'))
                ->with('error', __('ecommerce::ecommerce.auth.alerts.register_error'));
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', __('ecommerce::ecommerce.auth.alerts.logout_success'));
    }

    public function verify(Request $request, CheckOtpAction $checkOtpAction)
    {
        try {
            $validated = $request->validate([
                'otp' => ['required', 'string', 'size:6'],
            ]);

            $userId = session()->get('ecommerce_user_id_for_verification');

            if (!$userId) {
                return back()
                    ->with('error', __('ecommerce::ecommerce.auth.validation.session_expired'));
            }

            $user = User::findOrFail($userId);
            $isValid = $checkOtpAction->handle($user, OTPTypes::USER_VERIFY_EMAIL, $validated['otp']);

            if (!$isValid) {
                return back()
                    ->withInput($request->only('otp'))
                    ->with('error', __('ecommerce::ecommerce.auth.validation.otp_invalid'));
            }

            $user->forceFill([
                'email_verified_at' => now(),
                'status' => 'active',
            ])->save();

            Auth::guard('web')->login($user);

            session()->forget([
                'ecommerce_user_id_for_verification',
                'ecommerce_user_email_for_verification',
                'debugOtp'
            ]);

            return redirect()->route('home')
                ->with('success', __('ecommerce::ecommerce.auth.alerts.otp_verified_success'));

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput($request->only('otp'));
        } catch (\Exception $e) {
            Log::error('OTP Verification Failed: ' . $e->getMessage());

            return back()
                ->withInput($request->only('otp'))
                ->with('error', __('ecommerce::ecommerce.auth.alerts.verification_error'));
        }
    }

    public function resendOtp(Request $request, SendOtpAction $sendOtpAction)
    {
        try {
            $userId = session()->get('ecommerce_user_id_for_verification');

            if (!$userId) {
                return redirect()->route('auth.register')
                    ->with('error', __('ecommerce::ecommerce.auth.validation.session_expired'));
            }

            $user = User::findOrFail($userId);
            $sendOtpAction->handleForMail($user, OTPTypes::USER_VERIFY_EMAIL, 120, throwOnFailure: true);

            return back()
                ->with('success', __('ecommerce::ecommerce.auth.alerts.otp_resent_success'));

        } catch (\Exception $e) {
            Log::error('Resend OTP Failed: ' . $e->getMessage());

            return back()
                ->with('error', __('ecommerce::ecommerce.auth.alerts.otp_resend_error'));
        }
    }

    public function getForgotPasswordPage(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('ecommerce::pages.auth.forgot-password', get_defined_vars());
    }

    public function sendResetLink(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
            ]);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return back()->with('success', __('ecommerce::ecommerce.auth.alerts.reset_link_sent'));
            }

            return back()
                ->withInput($request->only('email'))
                ->with('error', __('ecommerce::ecommerce.auth.validation.reset_link_failed'));

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput($request->only('email'));
        } catch (\Exception $e) {
            Log::error('Forgot Password Failed: ' . $e->getMessage());
            return back()
                ->withInput($request->only('email'))
                ->with('error', __('ecommerce::ecommerce.auth.alerts.reset_link_error'));
        }
    }

    public function showResetForm(Request $request, $token)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        $email = $request->get('email');

        if ($email && $user = User::where('email', $email)->first()) {
            if (!Password::broker()->tokenExists($user, $token)) {
                return redirect()->route('password.request')
                    ->with('error', __('ecommerce::ecommerce.auth.validation.reset_link_invalid'));
            }
        }

        return view('ecommerce::pages.auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token' => ['required'],
                'email' => ['required', 'email'],
                'password' => ['required', 'confirmed', PasswordRule::defaults()],
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return redirect()->route('auth.login')
                    ->with('success', __('ecommerce::ecommerce.auth.alerts.password_reset_success'));
            }

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput($request->only('email'));
        } catch (\Exception $e) {
            Log::error('Password Reset Failed: ' . $e->getMessage());
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __('ecommerce::ecommerce.auth.alerts.reset_link_error')]);
        }
    }
}
