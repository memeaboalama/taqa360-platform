<?php

namespace Application\Educator\Livewire\Auth;

use App\Domain\Otp\Actions\SendOtpAction;
use App\Support\Enum\OTPTypes;
use Domain\Educator\Actions\Auth\RegisterEducatorAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Register extends Component
{
    use LivewireAlert;

    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $agree_terms = false;

    protected function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:2', 'max:50'],
            'last_name' => ['required', 'string', 'min:2', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:educators,email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
            'agree_terms' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'agree_terms.accepted' => __('educator::auth.validation.agree_terms'),
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function register(RegisterEducatorAction $registerAction, SendOtpAction $sendOtpAction)
    {
        $validated = $this->validate();
        try {
            $educator = $registerAction->handle([
                'name' => "{$validated['first_name']} {$validated['last_name']}",
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            // ---------------------------------------------------------------------------
            // [DISABLED - email verification, comment-only] The OTP was emailed here and
            // the user was forced onto the /verify page (educator:verify) before being
            // allowed in. Commented out (not deleted) so registration completes without a
            // confirmation code. To restore: uncomment the block below and re-comment the
            // temporary replacement that follows it.
            // ---------------------------------------------------------------------------
            // $code = $sendOtpAction->handleForMail($educator, OTPTypes::USER_VERIFY_EMAIL, 120);

            // session()->put('educator_id_for_verification', $educator->id);
            // session()->put('educator_email_for_verification', $educator->email);
            // return redirect()->route('educator:verify');

            // [TEMPORARY REPLACEMENT - email verification disabled] activate the account
            // and sign the user in straight away (same end state as passing /verify).
            $educator->forceFill(['email_verified_at' => now(), 'status' => 'active'])->save();
            Auth::guard('educator')->login($educator);
            session()->forget(['educator_id_for_verification', 'educator_email_for_verification']);

            return redirect()->route('educator:home');

        } catch (\Exception $e) {
            logger()->error('Educator Registration Failed: ' . $e->getMessage());
            Log::info('Educator Registration Failed: ' . $e->getMessage());
            $this->alert('error', __('educator::auth.alerts.register_error'));
        }
    }

    public function render()
    {
        return view('educator::livewire.auth.register');
    }
}
