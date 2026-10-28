<?php

namespace App\Domain\Driver\Actions\Auth;

use App\Support\Services\Auth\AuthService;
use Domain\Driver\Models\Driver;

class LoginAction
{
    protected AuthService $loginService;

    public function __construct()
    {
        $this->loginService = new AuthService('driver');
    }

    public function handle(array $data = []): Driver|bool|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        return $this->loginService->authenticate($data);
    }
}