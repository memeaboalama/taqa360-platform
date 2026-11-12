<?php

namespace App\Application\Admin\Controllers\Api\Edu;

use App\Application\Api\Requests\Edu\Service\StoreServiceRequest;
use App\Application\Api\Requests\Edu\Service\UpdateServiceRequest;
use App\Domain\AppBase\Actions\Edu\Service\CreateServiceAction;
use App\Domain\AppBase\Actions\Edu\Service\UpdateServiceAction;
use App\Domain\AppBase\Resources\Edu\ServiceResource;
use App\Http\Controllers\Controller;
use App\Support\Enum\StatusEnum;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\AppBase\Models\Edu\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ServiceController extends Controller
{
    use ApiResponseTrait;

    public function options(): JsonResponse
    {
        return $this->responseData([
            'services' => ServiceResource::collection(
                Service::where('status', StatusEnum::ACTIVE->value)->get()
            ),
        ], 200, 'Services retrieved successfully');
    }

    public function index(Request $request): JsonResponse
    {
        $query = Service::query();
        $search = trim((string) $request->input('search', ''));

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        $services = $query->paginate($request->get('per_page', 15));
        return $this->responseData([
            'services' => ServiceResource::collection($services)
        ], 200, 'Services retrieved successfully');
    }

    public function show(Service $service): JsonResponse
    {
        return $this->responseData([
            'service' => ServiceResource::make($service)
        ], 200, 'Service retrieved successfully');
    }

    public function store(StoreServiceRequest $request, CreateServiceAction $createServiceAction): JsonResponse
    {
        $service = $createServiceAction->execute($request->validated());
        return $this->responseData([
            'service' => ServiceResource::make($service)
        ], 201, 'Service created successfully');
    }

    public function update(UpdateServiceRequest $request, Service $service, UpdateServiceAction $updateServiceAction): JsonResponse
    {
        $service = $updateServiceAction->execute($service, $request->validated());
        return $this->responseData([
            'service' => ServiceResource::make($service)
        ], 200, 'Service updated successfully');
    }

    public function destroy(Service $service): JsonResponse
    {
        $isUsedByEducator = Schema::hasTable('educator_services')
            && $service->educatorServices()->exists();
        $isUsedByRequest = Schema::hasTable('educational_requests')
            && DB::table('educational_requests')->where('service_id', $service->getKey())->exists();

        if ($isUsedByEducator || $isUsedByRequest) {
            return $this->errorResponse(409, 'SERVICE_IN_USE');
        }

        $service->delete();
        return $this->responseData([], 200, 'Service deleted successfully');
    }

    public function statusToggle(Service $service): JsonResponse
    {
        $service->status = $service->status === StatusEnum::ACTIVE
            ? StatusEnum::INACTIVE
            : StatusEnum::ACTIVE;

        $service->save();

        return $this->responseData([
            'service' => ServiceResource::make($service)
        ], 200, 'Service status toggled successfully');
    }
}
