<?php

namespace App\Application\Educator\Controllers\Api\Profile;

use App\Application\Educator\Requests\Profile\ServiceRequest;
use App\Domain\Educator\Resource\Profile\EducatorServiceResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Domain\Educator\Actions\Profile\UpdateServiceAction;
use Domain\Educator\Models\EducatorService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use ApiResponseTraitV2;

    public function fetch(Request $request)
    {
        $educator = $request->user();
        $services = $educator->services()->with('approvedService')->get();
        return $this->responseData(['services' => EducatorServiceResource::collection($services)]);
    }

    public function store(ServiceRequest $request, UpdateServiceAction $action)
    {
        $educator = $request->user();
        $action->handle($educator, $request->validated());
        return $this->responseData(['services' => EducatorServiceResource::collection($educator->services()->with('approvedService')->get())], msg:['message'=>'Service added successfully']);
    }

    public function update(ServiceRequest $request, EducatorService $service, UpdateServiceAction $action)
    {
        $educator = $request->user();
        $data = $request->validated() + ['id' => $service->id];
        $action->handle($educator, $data);
        return $this->responseData(['services' => EducatorServiceResource::collection($educator->services()->with('approvedService')->get())], msg:['message'=>'Service updated successfully']);
    }

    public function destroy(EducatorService $service, UpdateServiceAction $action)
    {
        $educator = auth()->user();
        $action->delete($educator, $service->id);
        return $this->responseData(msg:'Service deleted successfully');
    }
}
