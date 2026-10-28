<?php

namespace Application\Driver\Livewire\Auth\Password;

use App\Domain\Driver\Actions\Auth\DetectDriverAction;
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
            'phone' => trans('driver::home.Phone Number'),
        ];
    }


    protected function rules(): array
    {
        return [
            'phone' =>  ['required','numeric','digits_between:9,10',new isValidPhone, 'exists:drivers,phone'],
        ];
    }


    public function forget(DetectDriverAction $detectDriverAction, SendOtpAction $sendOtpAction)
    {
        $data = $this->validate(attributes: $this->nickNames());
        $driver = $detectDriverAction->detectDriverByPhone($data);
        $sendOtpAction->handle($driver, OTPTypes::USER_RESET_PASS, 60);
        Session::put('phone', $data['phone']);
        return redirect()->route('driver:verifyOTP');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('driver::livewire.auth.password.forget');
    }
}
