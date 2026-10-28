<?php

namespace Application\Educator\Livewire\Auth;

use App\Domain\Otp\Actions\CheckOtpAction;
use App\Domain\Otp\Actions\SendOtpAction;
use App\Domain\Otp\Actions\VerifyOtpAction;
use App\Support\Enum\OTPTypes;
use Domain\Educator\Models\Educator;
use Exception;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Verify extends Component
{
    use LivewireAlert;

    public array $otp = ['', '', '', '', '', ''];
    public ?Educator $educator;
    public ?string $email;
    public int $cooldownExpiresAt;

    protected function rules(): array
    {
        return [
            'otp' => ['required', 'array', 'size:6'],
            'otp.*' => ['required', 'numeric', 'digits:1'],
        ];
    }

    public function mount()
    {
        $educatorId = session('educator_id_for_verification');
        $this->email = session('educator_email_for_verification');
        $this->educator = Educator::find($educatorId);

        if (!$this->educator || !$this->email) {
            $this->alert('error', __('educator::auth.alerts.session_expired'));
            return redirect()->route('educator:register');
        }
        $this->startCooldown();
    }

    private function startCooldown()
    {
        $this->cooldownExpiresAt = now()->addSeconds(120)->timestamp;
    }

    public function verifyOtp(CheckOtpAction $verifyOtpAction)
    {
        $this->validate();
        if (!$this->educator) return redirect()->route('educator:register');

        $isValid = $verifyOtpAction->handle($this->educator, OTPTypes::USER_VERIFY_EMAIL, implode('', $this->otp));

        if (!$isValid) {
            $this->addError('otp', __('educator::auth.validation.otp_invalid'));
            return;
        }

        $this->educator->forceFill(['email_verified_at' => now(), 'status' => 'active'])->save();
        Auth::guard('educator')->login($this->educator);
        session()->forget(['educator_id_for_verification', 'educator_email_for_verification']);

        return redirect()->route('educator:home');
    }

    public function resendCode(SendOtpAction $sendOtpAction)
    {
        if (!$this->educator || (now()->timestamp < $this->cooldownExpiresAt)) {
            return;
        }

        try {
            $sendOtpAction->handleForMail($this->educator, OTPTypes::USER_VERIFY_EMAIL, 120);
            $this->alert('success', __('educator::auth.alerts.otp_resent_success'));
            $this->startCooldown();
            $this->dispatchBrowserEvent('cooldown-updated', ['expiresAt' => $this->cooldownExpiresAt]);
        } catch (Exception $e) {
            logger()->error('Resend OTP Failed: ' . $e->getMessage());
            $this->alert('error', __('educator::auth.alerts.otp_resent_error'));
        }
    }

    public function render()
    {
        return view('educator::livewire.auth.verify');
    }
}
