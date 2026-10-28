<?php

namespace Domain\Educator\Actions\Auth;

use App\Support\Services\Auth\AuthService;
use Domain\Educator\Models\Educator;

class LoginAction
{
    protected AuthService $loginService;

    public function __construct()
    {
        $this->loginService = new AuthService('educator');
    }

    public function handle(array $data = []): Educator|bool|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        return $this->loginService->authenticate($data);
    }
}

