<?php

namespace Application\Admin\Livewire\Auth\Password;

use App\Domain\Admin\Actions\Dashboard\Auth\ResetPasswordAction;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Reset extends Component
{
    use LivewireAlert;

    public $password, $password_confirmation, $email;

    public function mount()
    {
        $this->email = Session::get('email');
    }

    public function nickNames()
    {
        return [
            'email' => trans('admin::home.email'),
            'password' => trans('admin::home.Password'),
        ];
    }

    protected function rules(): array
    {
        return [
            'email' =>  ['required','string', 'exists:admins,email'],
            'password' => 'required|string|confirmed|min:4',
        ];
    }

    public function resetPassword(ResetPasswordAction $resetPasswordAction)
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $admin = $resetPasswordAction->handle($data);
        DB::commit();
        if ($admin) return redirect(RouteServiceProvider::ADMIN_HOME);
        $this->alert('success', 'Password Changed Successfully');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.auth.password.reset');
    }
}
