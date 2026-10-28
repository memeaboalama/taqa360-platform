<?php

namespace App\Support\Services\Auth;

use Illuminate\Support\Facades\Auth;

class Guard
{
    public static function get()
    {
        $guards = array_keys(config('auth.guards'));
        foreach ($guards as $guard) {
            if(Auth::guard($guard)->check())
                return $guard;
        }
        return "";
    }
}
