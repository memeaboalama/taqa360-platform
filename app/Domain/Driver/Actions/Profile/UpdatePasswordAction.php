<?php

namespace App\Domain\Driver\Actions\Profile;

use Domain\Driver\Models\Driver;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordAction
{
    public function handle(array $data = []): ?Driver
    {
        $driver = auth()->guard('driver')->user();
        if (!$driver) {
            return null;
        }

        $driver->update([
            'password' => Hash::make($data['new_password'])
        ]);

        return $driver;
    }
}