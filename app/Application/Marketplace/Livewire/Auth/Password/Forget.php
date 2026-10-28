<?php

namespace Application\Marketplace\Livewire\Auth\Password;

use App\Domain\Merchant\Actions\Auth\DetectMerchantAction;
use App\Domain\Otp\Actions\SendOtpAction;
use App\Support\Enum\OTPTypes;
use App\Support\Rules\IsValidPhone;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Forget extends Component
{
    use LivewireAlert;

    public $phone;

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
        ];
    }


    public function forget(DetectMerchantAction $detectMerchantAction, SendOtpAction $sendOtpAction)
    {
        $data = $this->validate(attributes: $this->nickNames());
        $merchant = $detectMerchantAction->detectMerchantByPhone($data);
        $sendOtpAction->handle($merchant, OTPTypes::USER_RESET_PASS, 60);
        Session::put('phone', $data['phone']);
        return redirect()->route('marketplace:verifyOTP');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.auth.password.forget');
    }
}
