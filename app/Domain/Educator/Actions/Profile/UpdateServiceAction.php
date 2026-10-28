<?php

namespace Domain\Educator\Actions\Profile;

use Domain\AppBase\Models\Edu\Service;
use Domain\Educator\Models\Educator;
use Illuminate\Support\Facades\DB;

class UpdateServiceAction
{
    public function handle(Educator $educator, array $serviceData)
    {
        DB::transaction(function () use ($educator, $serviceData) {
            $id = $serviceData['id'] ?? null;
            $serviceId = Service::where('uuid', $serviceData['service_id'] ?? null)->value('id') ?? null;

            if (!$serviceData['is_custom']) {
                $serviceData['service_id'] = $serviceId;
            }
            else {
                $serviceData['service_id'] = null;
            }

            $data = [
                'service_id' => $serviceData['is_custom'] ? null : ($serviceData['service_id'] ?? null),
                'custom_name' => $serviceData['is_custom'] ? ($serviceData['custom_name'] ?? null) : null,
                'custom_description' => $serviceData['custom_description'] ?? null,
                'is_custom' => (bool)($serviceData['is_custom'] ?? false),
            ];
            $educator->services()->updateOrCreate(['id' => $id], $data);
        });
    }

    public function delete(Educator $educator, int $id)
    {
        $educator->services()->find($id)?->delete();
    }
}
