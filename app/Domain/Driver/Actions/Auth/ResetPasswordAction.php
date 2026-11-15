<?php

namespace App\Domain\Driver\Actions\Auth;

use Domain\Driver\Models\Driver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResetPasswordAction
{
    public function handle(array $data = []): Driver
    {
        $driver = DB::transaction(function () use ($data): Driver {
            $driver = Driver::query()
                ->where('phone', $data['phone'])
                ->firstOrFail();

            $driver->password = $data['password'];
            $driver->save();

            return $driver;
        });
        Auth::guard('driver')->login($driver);

        return $driver;
    }
}
