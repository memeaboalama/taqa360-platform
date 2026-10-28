<?php

namespace Application\Admin\Livewire\Auth\Password;

use App\Domain\Admin\Actions\Dashboard\Auth\DetectAdminAction;
use App\Domain\Otp\Actions\CheckOtpAction;
use App\Domain\Otp\Actions\InvalidateOldOtpAction;
use App\Domain\Otp\Actions\SendOtpAction;
use App\Mail\Admin\ResetPasswordMail;
use App\Providers\RouteServiceProvider;
use App\Support\Enum\OTPTypes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Verify extends Component
{
    use LivewireAlert;

    public $email, $otp;
    protected $listeners = ['resend_otp'];

    public function mount()
    {
        $this->email = Session::get('email');
    }

    public function nickNames()
    {
        return [
            'email' => trans('marketplace::home.email'),
        ];
    }
    protected function rules(): array
    {
        return [
            'email' =>  ['required','string', 'exists:admins,email'],
            'otp' => ['required' , 'string' ,'min:4','max:4']
        ];
    }

    public function verify(CheckOtpAction $checkOtpAction, InvalidateOldOtpAction $invalidateOldOtpAction, DetectAdminAction $detectAdminAction)
    {
        $data = $this->validate(attributes: $this->nickNames());
        $admin = $detectAdminAction->detectAdminByEmail($data);
        $otpStatus = $checkOtpAction->handle($admin, OTPTypes::ADMIN_RESET_PASS, $this->otp);
        if (!$otpStatus)
        {
            $errors = $this->getErrorBag();
            return $errors->add('otp', 'OTP is not valid');
        }else{
            $invalidateOldOtpAction->handle($admin, OTPTypes::ADMIN_RESET_PASS);
            return redirect()->route('admin.resetPassword');
        }
    }


    public function resend_otp(SendOtpAction $sendOtpAction, DetectAdminAction $detectAdminAction)
    {
        $admin = $detectAdminAction->detectAdminByEmail(['email' => $this->email]);
        $code = $sendOtpAction->handleForMail($admin, OTPTypes::ADMIN_RESET_PASS, 60);
        Mail::to($admin->email)->send(new ResetPasswordMail($code));
        $this->alert('success', 'OTP Sent Successfully');
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.auth.password.verify');
    }
}
