<?php

namespace App\Application\Ecommerce\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Enum\StatusEnum;
use App\Domain\AppBase\Resources\Edu\ServiceResource;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\AppBase\Models\Edu\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class ServiceCatalogController extends Controller
{
    use ApiResponseTrait;

    public function index(): View
    {
        abort_unless(config('app.brand') === 'taqa360', 404);

        return view('ecommerce::pages.service-catalog');
    }

    public function show(string $serviceUuid): View
    {
        abort_unless(config('app.brand') === 'taqa360', 404);

        return view('ecommerce::pages.service-details', compact('serviceUuid'));
    }

    public function showData(string $serviceUuid): JsonResponse
    {
        abort_unless(config('app.brand') === 'taqa360', 404);

        $service = Service::query()
            ->where('uuid', $serviceUuid)
            ->where('status', StatusEnum::ACTIVE->value)
            ->firstOrFail();

        return $this->responseData([
            'service' => ServiceResource::make($service),
        ], 200, 'Service retrieved successfully');
    }
}
