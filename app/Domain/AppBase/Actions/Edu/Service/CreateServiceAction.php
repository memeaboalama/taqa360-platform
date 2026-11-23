<?php

namespace App\Domain\AppBase\Actions\Edu\Service;

use Domain\AppBase\Models\Edu\Service;

class CreateServiceAction
{
    public function execute(array $data): Service
    {
        if (($data['pricing_mode'] ?? 'quote') === 'quote') {
            $data['price'] = null;
        }
        return Service::create($data);
    }
}
