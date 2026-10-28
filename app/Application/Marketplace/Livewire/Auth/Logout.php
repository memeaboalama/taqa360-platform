<?php

namespace Application\Marketplace\Livewire\Auth;

use App\Domain\MarketPlace\Actions\Dashboard\Auth\LogoutAction;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Logout extends Component
{
    use LivewireAlert;

    public function logout(LogoutAction $logoutAction)
    {
        $logoutAction->handle();
        return redirect(route('marketplace:login'));
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.auth.logout');
    }
}
