<?php

namespace Application\Marketplace\Livewire\Profile\Verify;

use App\Domain\Merchant\Actions\Auth\DetectMerchantAction;
use App\Domain\Otp\Actions\CheckOtpAction;
use App\Domain\Otp\Actions\InvalidateOldOtpAction;
use App\Domain\Otp\Actions\SendOtpAction;
use App\Mail\Marketplace\ConfirmMail;
use App\Providers\RouteServiceProvider;
use App\Support\Enum\OTPTypes;
use Application\Admin\Livewire\Settings\Tabs\Content\Auth;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Email extends Component
{
    use LivewireAlert;

    public $otp;
    protected $listeners = ['resend_otp'];
    protected function rules(): array
    {
        return [
            'otp' => ['required' , 'string' ,'min:4','max:6']
        ];
    }

    public function verify(CheckOtpAction $checkOtpAction, InvalidateOldOtpAction $invalidateOldOtpAction)
    {
        $data = $this->validate();
        $merchant = \auth()->guard('merchant')->user();
        $otpStatus = $checkOtpAction->handle($merchant, OTPTypes::MERCHANT_VERIFY_EMAIL, $this->otp);

        if (!$otpStatus)
        {
            $errors = $this->getErrorBag();
            return $errors->add('otp', __('marketplace::home.OTP is not valid'));
        }else{
            $invalidateOldOtpAction->handle($merchant, OTPTypes::MERCHANT_VERIFY_EMAIL);
            $merchant->update([
                'email_verified_at' => Carbon::now()
            ]);
            $this->alert('success', __('marketplace::home.data_activated_successfully'));
            return redirect(RouteServiceProvider::MARKETPLACE_HOME);
        }
    }


    public function resend_otp(SendOtpAction $sendOtpAction)
    {
        $merchant = \auth()->guard('merchant')->user();

        $code = $sendOtpAction->handleForMail($merchant, OTPTypes::MERCHANT_VERIFY_EMAIL, 60);
        Mail::to($merchant->email)->send(new ConfirmMail($code));
        $this->alert('success', __('marketplace::home.data_sent_successfully'));
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.profile.verify.email');
    }
}
