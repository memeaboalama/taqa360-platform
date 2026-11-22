<?php

namespace App\Domain\AppBase\Actions\Edu\Service;

use Domain\AppBase\Models\Edu\Service;

class UpdateServiceAction
{
    public function execute(Service $service, array $data): Service
    {
        if (isset($service->details['_seed_key'])) {
            $data['details'] = is_array($data['details'] ?? null) ? $data['details'] : [];
            $data['details']['_seed_key'] = $service->details['_seed_key'];
        }

        $service->update($data);
        return $service;
    }
}
