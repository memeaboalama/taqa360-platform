<?php

namespace App\Domain\Ecommerce\Auth;

use App\Domain\User\Models\User;
use App\Support\Services\Auth\AuthService;

class LogoutAction
{
    protected AuthService $loginService;

    public function __construct()
    {
        $this->loginService = new AuthService('web');
    }

    public function handle(): User|bool|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        return $this->loginService->logout();
    }
}
