<?php

namespace App\Domain\AppBase\Actions\Edu\Service;

use Domain\AppBase\Models\Edu\Service;

class UpdateServiceAction
{
    public function execute(Service $service, array $data): Service
    {
        $service->update($data);
        return $service;
    }
}
