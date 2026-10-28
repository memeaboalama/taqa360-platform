<?php

namespace Application\Educator\Livewire\Auth;

use Domain\Educator\Actions\Auth\LoginAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;
    public bool $redirecting = false;
    public string $welcomeName = '';
    public string $redirectUrl = '';

    protected function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function login(LoginAction $loginAction)
    {
        $this->validate();

        if (!Auth::guard('educator')->attempt($this->only(['email', 'password']), $this->remember)) {
            throw ValidationException::withMessages([
                'email' => __('educator::auth.validation.credentials_do_not_match'),
            ]);
        }

        request()->session()->regenerate();

        $user = Auth::guard('educator')->user();
        $this->welcomeName = $user->name ?? $this->email;
        $this->redirectUrl = route('educator:home');
        $this->redirecting = true;
        session()->flash('login_welcome', __('educator::auth.welcome_back_msg', ['name' => $this->welcomeName]));
        return;
    }

    public function render()
    {
        return view('educator::livewire.auth.login');
    }
}
