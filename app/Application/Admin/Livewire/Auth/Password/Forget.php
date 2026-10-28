<?php

namespace Application\Admin\Livewire\Auth\Password;

use App\Domain\Admin\Actions\Dashboard\Auth\DetectAdminAction;
use App\Domain\Otp\Actions\SendOtpAction;
use App\Mail\Admin\ResetPasswordMail;
use App\Support\Enum\OTPTypes;
use App\Support\Rules\IsValidPhone;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Forget extends Component
{
    use LivewireAlert;

    public $email;

    public function nickNames()
    {
        return [
            'email' => trans('admin::home.email'),
        ];
    }


    protected function rules(): array
    {
        return [
            'email' =>  ['required','string', 'exists:admins,email'],
        ];
    }


    public function forget(DetectAdminAction $detectAdminAction, SendOtpAction $sendOtpAction)
    {
        $data = $this->validate(attributes: $this->nickNames());
        $admin = $detectAdminAction->detectAdminByEmail($data);
        $code = $sendOtpAction->handleForMail($admin, OTPTypes::ADMIN_RESET_PASS, 60);
        Mail::to($data['email'])->send(new ResetPasswordMail($code));
        Session::put('email', $data['email']);
        return redirect()->route('admin.verifyOTP');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.auth.password.forget');
    }
}
