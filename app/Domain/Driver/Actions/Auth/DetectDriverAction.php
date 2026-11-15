<?php

namespace App\Domain\Driver\Actions\Auth;

use Domain\Driver\Models\Driver;

class DetectDriverAction
{
    public function detectDriverByPhone(array $data): Driver
    {
        return Driver::query()
            ->where('phone', $data['phone'])
            ->firstOrFail();
    }
}
