<?php

namespace Domain\Admin\Actions\Dashboard\Auth;

use App\Support\Services\Auth\AuthService;
use Domain\Admin\Models\Admin;

class LogoutAction
{
    protected AuthService $loginService;

    public function __construct()
    {
        $this->loginService = new AuthService('admin');
    }

    public function handle(): Admin|bool|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        return $this->loginService->logout();
    }
}
