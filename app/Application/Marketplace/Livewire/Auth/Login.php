<?php

namespace Application\Marketplace\Livewire\Auth;

use App\Domain\MarketPlace\Actions\Dashboard\Auth\Employee\LoginEmployeeAction;
use App\Domain\MarketPlace\Actions\Dashboard\Auth\LoginAction;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Login extends Component
{
    use LivewireAlert;

    public string $email, $password;
    public bool $redirecting = false;
    public string $welcomeName = '';
    public string $redirectUrl = '';

    protected function rules(): array
    {
        return [
            'email' => 'required|email|min:3',
            'password' => 'required|string|min:4',
        ];
    }

    public function store(LoginAction $loginAction, LoginEmployeeAction $loginEmployeeAction)
    {
        $data = $this->validate();
        $res = $loginAction->handle($data);
        if (!$res)
            $res = $loginEmployeeAction->handle($data);
        if ($res) {
            $user = auth('merchant')->user() ?? auth('merchant_employee')->user();
            $this->welcomeName = $user->user_name ?? $user->name ?? $this->email;
            $this->redirectUrl = route('marketplace:home');
            $this->redirecting = true;
            session()->flash('login_welcome', __('marketplace::home.welcome_back_msg', ['name' => $this->welcomeName]));
            return;
        }
        $errors = $this->getErrorBag();
        return $errors->add('email', "this credential doesn't match our records");
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.auth.login');
    }
}
