<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Auth;

use App\Support\Services\Auth\AuthService;

class LogoutAction
{
    protected $loginService;

    public function __construct()
    {
        $this->loginService = new AuthService('merchant');
    }

    public function handle(): bool
    {
        $this->loginService->logout();
        (new AuthService('merchant_employee'))->logout();
        return true;
    }
}
