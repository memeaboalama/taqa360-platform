<?php

namespace App\Domain\AppBase\Actions\Edu\Service;

use Domain\AppBase\Models\Edu\Service;

class UpdateServiceAction
{
    public function execute(Service $service, array $data): Service
    {
        if (($data['pricing_mode'] ?? $service->pricing_mode ?? 'quote') === 'quote') {
            $data['price'] = null;
        }
        if (isset($service->request_config['_seed_key'])) {
            $data['request_config'] = is_array($data['request_config'] ?? null)
                ? $data['request_config']
                : $service->request_config;
            $data['request_config']['_seed_key'] = $service->request_config['_seed_key'];
        }

        $service->update($data);
        return $service;
    }
}
