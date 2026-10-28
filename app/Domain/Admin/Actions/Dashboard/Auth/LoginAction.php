<?php

namespace Domain\Admin\Actions\Dashboard\Auth;

use App\Support\Services\Auth\AuthService;
use Domain\Admin\Models\Admin;

class LoginAction
{
    protected AuthService $loginService;

    public function __construct()
    {
        $this->loginService = new AuthService('admin');
    }

    public function handle(array $data = []): Admin|bool|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        return $this->loginService->authenticate($data);
    }
}

