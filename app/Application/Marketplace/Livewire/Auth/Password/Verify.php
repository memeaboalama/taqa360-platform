<?php

namespace Application\Marketplace\Livewire\Auth\Password;

use App\Domain\Merchant\Actions\Auth\DetectMerchantAction;
use App\Domain\Otp\Actions\CheckOtpAction;
use App\Domain\Otp\Actions\InvalidateOldOtpAction;
use App\Support\Enum\OTPTypes;
use App\Support\Rules\IsValidPhone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Verify extends Component
{
    use LivewireAlert;

    public $phone, $otp;

    public function mount()
    {
        $this->phone = Session::get('phone');
    }

    public function nickNames()
    {
        return [
            'phone' => trans('marketplace::home.Phone Number'),
        ];
    }

    protected function rules(): array
    {
        return [
            'phone' =>  ['required','numeric','digits_between:9,10',new isValidPhone, 'exists:merchants,phone'],
            'otp' => ['required' , 'string' ,'min:4','max:6']
        ];
    }

    public function verify(DetectMerchantAction $detectMerchantAction, CheckOtpAction $checkOtpAction, InvalidateOldOtpAction $invalidateOldOtpAction)
    {
        $data = $this->validate(attributes: $this->nickNames());
        $merchant = $detectMerchantAction->detectMerchantByPhone($data);
        $otpStatus = $checkOtpAction->handle($merchant, OTPTypes::USER_RESET_PASS, $this->otp);
        if (!$otpStatus)
        {
            $errors = $this->getErrorBag();
            return $errors->add('otp', __('marketplace::home.OTP is not valid'));
        }else{
            $invalidateOldOtpAction->handle($merchant, OTPTypes::USER_RESET_PASS);
            return redirect()->route('marketplace:resetPassword');
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.auth.password.verify');
    }
}
