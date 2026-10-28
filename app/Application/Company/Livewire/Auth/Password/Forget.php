<?php

namespace Application\Company\Livewire\Auth\Password;

use App\Domain\Company\Actions\Dashboard\Auth\DetectCompanyAction;
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
            'phone' => trans('company::home.Phone Number'),
        ];
    }


    protected function rules(): array
    {
        return [
            'phone' =>  ['required','numeric','digits_between:9,10',new isValidPhone, 'exists:companies,phone'],
        ];
    }


    public function forget(DetectCompanyAction $detectCompanyAction, SendOtpAction $sendOtpAction)
    {
        $data = $this->validate(attributes: $this->nickNames());
        $merchant = $detectCompanyAction->detectCompanyByPhone($data);
        $sendOtpAction->handle($merchant, OTPTypes::COMPANY_RESET_PASS, 60);
        Session::put('company_phone', $data['phone']);
        return redirect()->route('company:verifyOTP');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('company::livewire.auth.password.forget');
    }
}
