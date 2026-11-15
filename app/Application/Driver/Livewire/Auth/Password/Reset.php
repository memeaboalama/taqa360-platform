<?php

namespace Application\Driver\Livewire\Auth\Password;

use App\Domain\Driver\Actions\Auth\ResetPasswordAction;
use App\Providers\RouteServiceProvider;
use App\Support\Rules\IsValidPhone;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Reset extends Component
{
    use LivewireAlert;

    public $password, $password_confirmation, $phone;

    public function mount()
    {
        $this->phone = Session::get('driver_password_reset_phone');

        if (!$this->phone) {
            return redirect()->route('driver:forgetPassword');
        }

        if (!Session::get('driver_password_reset_verified')) {
            return redirect()->route('driver:verifyOTP');
        }
    }

    public function nickNames()
    {
        return [
            'phone' => trans('driver::home.Phone Number'),
            'password' => trans('driver::home.Password'),
        ];
    }

    protected function rules(): array
    {
        return [
            'phone' =>  ['required','numeric','digits_between:9,10',new isValidPhone, 'exists:drivers,phone'],
            'password' => 'required|string|confirmed|min:4',
        ];
    }

    public function resetPassword(ResetPasswordAction $resetPasswordAction)
    {
        if (!Session::get('driver_password_reset_phone')) {
            return redirect()->route('driver:forgetPassword');
        }

        if (!Session::get('driver_password_reset_verified')) {
            return redirect()->route('driver:verifyOTP');
        }

        $this->phone = Session::get('driver_password_reset_phone');
        $data = $this->validate(attributes: $this->nickNames());
        $resetPasswordAction->handle($data);
        Session::forget(['driver_password_reset_phone', 'driver_password_reset_verified']);

        return redirect(RouteServiceProvider::DRIVER_HOME);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('driver::livewire.auth.password.reset');
    }
}
