<?php

namespace Application\Admin\Livewire\Auth;

use Domain\Admin\Actions\Dashboard\Auth\LogoutAction;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Logout extends Component
{
    use LivewireAlert;

    public function logout(LogoutAction $logoutAction): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $logoutAction->handle();
        return redirect(route('admin.login'));
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.auth.logout');
    }
}
