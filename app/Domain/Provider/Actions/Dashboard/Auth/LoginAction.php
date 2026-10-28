<?php

namespace App\Domain\Provider\Actions\Dashboard\Auth;

use App\Support\Services\Auth\AuthService;

class LoginAction
{
    protected $loginService;

    public function __construct()
    {
        $this->loginService = new AuthService('company');
    }

    public function handle(array $data = []): \Domain\MarketPlace\Models\Merchant|bool|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        return $this->loginService->authenticate($data);
    }
}

