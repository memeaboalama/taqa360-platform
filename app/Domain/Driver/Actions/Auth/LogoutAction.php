<?php

namespace App\Domain\Driver\Actions\Auth;

use App\Support\Services\Auth\AuthService;
use Domain\Driver\Models\Driver;

class LogoutAction
{
    protected AuthService $loginService;

    public function __construct()
    {
        $this->loginService = new AuthService('driver');
    }

    public function handle(): Driver|bool|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        return $this->loginService->logout();
    }
}