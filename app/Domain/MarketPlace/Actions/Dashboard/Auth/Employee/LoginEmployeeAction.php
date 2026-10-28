<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Auth\Employee;

use App\Support\Services\Auth\AuthService;

class LoginEmployeeAction
{
    protected $loginService;

    public function __construct()
    {
        $this->loginService = new AuthService('merchant_employee');
    }

    public function handle(array $data = [])
    {
        return $this->loginService->authenticate($data);
    }
}
