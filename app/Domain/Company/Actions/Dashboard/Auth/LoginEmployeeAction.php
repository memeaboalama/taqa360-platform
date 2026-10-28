<?php

namespace App\Domain\Company\Actions\Dashboard\Auth;

use App\Support\Services\Auth\AuthService;

class LoginEmployeeAction
{
    protected $loginService;

    public function __construct()
    {
        $this->loginService = new AuthService('company_employee');
    }

    public function handle(array $data = [])
    {
        return $this->loginService->authenticate($data);
    }
}
