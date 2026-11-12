<?php

namespace Application\Admin\Livewire\Auth;

use Domain\Admin\Actions\Dashboard\Auth\LoginAction;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Login extends Component
{
    use LivewireAlert;

    public string $email, $password;

    protected function rules(): array
    {
        return [
            'email' => 'required|email|min:3',
            'password' => 'required|string|min:4',
        ];
    }

    public function store(LoginAction $loginAction)
    {
        $data = $this->validate();
        $res = $loginAction->handle($data);
        if ($res) {
            $user = auth('admin')->user();
            session()->flash('login_welcome', __('admin::home.welcome_back_msg', ['name' => $user->name ?? $this->email]));
            return redirect()->route('admin:home');
        }
        $errors = $this->getErrorBag();
        return $errors->add('email', "Oops! Your username or password is incorrect. Please try again");
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.auth.login');
    }
}
