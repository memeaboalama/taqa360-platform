<?php

namespace Application\Driver\Livewire\Auth\Password;

use App\Domain\Driver\Actions\Auth\DetectDriverAction;
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
            'phone' => trans('driver::home.Phone Number'),
        ];
    }

    protected function rules(): array
    {
        return [
            'phone' =>  ['required','numeric','digits_between:9,10',new isValidPhone, 'exists:drivers,phone'],
            'otp' => ['required' , 'string' ,'min:4','max:4']
        ];
    }

    public function verify(DetectDriverAction $detectDriverAction, CheckOtpAction $checkOtpAction, InvalidateOldOtpAction $invalidateOldOtpAction)
    {
        $data = $this->validate(attributes: $this->nickNames());
        $driver = $detectDriverAction->detectDriverByPhone($data);
        $otpStatus = $checkOtpAction->handle($driver, OTPTypes::USER_RESET_PASS, $this->otp);
        if (!$otpStatus)
        {
            $errors = $this->getErrorBag();
            return $errors->add('otp', __('driver::home.OTP is not valid'));
        }else{
            $invalidateOldOtpAction->handle($driver, OTPTypes::USER_RESET_PASS);
            return redirect()->route('driver:resetPassword');
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('driver::livewire.auth.password.verify');
    }
}
