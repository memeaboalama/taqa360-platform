<?php

namespace App\Support\Services\Auth;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    public $guard;

    public function __construct(string $guard)
    {
        $this->guard = $guard;
    }

    public function authenticate(array $credentials) : bool
    {
        return auth()->guard($this->guard)->attempt($credentials);
    }

    public function logout()
    {
        return auth($this->guard)->logout();
    }

    public function getProfile(){
        return auth($this->guard)->user();
    }
}
