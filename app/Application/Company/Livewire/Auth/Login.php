<?php

namespace Application\Company\Livewire\Auth;

use App\Domain\Company\Actions\Dashboard\Auth\LoginEmployeeAction;
use App\Domain\Provider\Actions\Dashboard\Auth\LoginAction;
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
            $user = auth('company')->user() ?? auth('company_employee')->user();
            $this->welcomeName = $user?->name ?? $this->email;
            $this->redirectUrl = route('company:home');
            $this->redirecting = true;
            session()->flash('login_welcome', __('company::home.welcome_back_msg', ['name' => $this->welcomeName]));
            return;
        }
        $errors = $this->getErrorBag();
        return $errors->add('email', "this credential doesn't match our records");
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('company::livewire.auth.login');
    }
}
