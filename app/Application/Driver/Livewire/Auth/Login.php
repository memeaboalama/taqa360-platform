<?php

namespace Application\Driver\Livewire\Auth;

use App\Domain\Driver\Actions\Auth\LoginAction;
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

    public function store(LoginAction $loginAction)
    {
        $data = $this->validate();
        $res = $loginAction->handle($data);
        if ($res)
        {
        //    $driverData= Driver::find(['email'=>$data["email"]])->first();
        //     DriverHistory::create(['driver_id'=>$driverData["id"]]);
            $user = auth('driver')->user();
            $this->welcomeName = $user->name ?? $this->email;
            $this->redirectUrl = route('driver:home');
            $this->redirecting = true;
            session()->flash('login_welcome', __('driver::home.welcome_back_msg', ['name' => $this->welcomeName]));
            return;
        }
        $errors = $this->getErrorBag();
        return $errors->add('email', "this credential doesn't match our records");
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('driver::livewire.auth.login');
    }
}
