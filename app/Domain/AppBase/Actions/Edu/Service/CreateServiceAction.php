<?php

namespace App\Domain\AppBase\Actions\Edu\Service;

use Domain\AppBase\Models\Edu\Service;

class CreateServiceAction
{
    public function execute(array $data): Service
    {
        return Service::create($data);
    }
}
