<?php

namespace Application\Company\Livewire\Auth;

use App\Domain\Provider\Actions\Dashboard\Auth\LogoutAction;
use App\Providers\RouteServiceProvider;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Logout extends Component
{
    use LivewireAlert;

    public function logout(LogoutAction $logoutAction)
    {
        $logoutAction->handle();
        return redirect(RouteServiceProvider::COMPANY_LOGIN);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('company::livewire.auth.logout');
    }
}
