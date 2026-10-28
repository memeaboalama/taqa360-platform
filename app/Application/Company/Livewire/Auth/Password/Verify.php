<?php

namespace Application\Company\Livewire\Auth\Password;

use App\Domain\Company\Actions\Dashboard\Auth\DetectCompanyAction;
use App\Domain\Otp\Actions\CheckOtpAction;
use App\Domain\Otp\Actions\InvalidateOldOtpAction;
use App\Support\Enum\OTPTypes;
use App\Support\Rules\IsValidPhone;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Verify extends Component
{
    use LivewireAlert;

    public $phone, $otp;

    public function mount()
    {
        $this->phone = Session::get('company_phone');
    }

    public function nickNames()
    {
        return [
            'phone' => trans('company::home.Phone Number'),
        ];
    }

    protected function rules(): array
    {
        return [
            'phone' =>  ['required','numeric','digits_between:9,10',new isValidPhone, 'exists:companies,phone'],
            'otp' => ['required' , 'string' ,'min:4','max:4']
        ];
    }

    public function verify(DetectCompanyAction $detectCompanyAction, CheckOtpAction $checkOtpAction, InvalidateOldOtpAction $invalidateOldOtpAction)
    {
        $data = $this->validate(attributes: $this->nickNames());
        $merchant = $detectCompanyAction->detectCompanyByPhone($data);
        $otpStatus = $checkOtpAction->handle($merchant, OTPTypes::COMPANY_RESET_PASS, $this->otp);
        if (!$otpStatus)
        {
            $errors = $this->getErrorBag();
            return $errors->add('otp', __('company::home.OTP is not valid'));
        }else{
            $invalidateOldOtpAction->handle($merchant, OTPTypes::COMPANY_RESET_PASS);
            return redirect()->route('company:resetPassword');
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('company::livewire.auth.password.verify');
    }
}
