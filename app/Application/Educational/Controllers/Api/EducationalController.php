<?php

namespace App\Application\Educational\Controllers\Api;

use App\Application\Educational\Enums\EducationalRequestStatus;
use App\Application\Educational\Enums\EducationalRequestType;
use App\Application\Educational\Requests\FetchEducationalRequest;
use App\Application\Educational\Requests\StoreEducationalRequest;
use App\Domain\EducationalRequest\Actions\CreateEducationalRequestAction;
use App\Domain\EducationalRequest\Actions\ReviewEducationalRequestAction;
use App\Domain\EducationalRequest\Resources\EducationalRequestResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Domain\EducationalRequest\Models\EducationalRequest;
use Domain\EducationalRequest\Repositories\EducationalRequestRepository;
use Illuminate\Http\Request;

class EducationalController extends Controller
{
    use ApiResponseTraitV2;
    public function __construct(
        private EducationalRequestRepository $repository,
        private CreateEducationalRequestAction $createAction,
        private ReviewEducationalRequestAction $reviewAction
    ) {}


    public function fetch(FetchEducationalRequest $request)
    {
        $requests = $this->repository->getUserRequests(
            auth()->id(),
            $request->all()
        );

        return $this->responseData(data:['educationalRequests' => EducationalRequestResource::collection($requests)]);
    }

    public function store(StoreEducationalRequest $request, CreateEducationalRequestAction $createAction)
    {
        try {
            $educationalRequest = $createAction->handle(
                array_merge($request->validated(), ['user_id' => 1])
            );

            return $this->responseData(data:['educationalRequest' => new EducationalRequestResource($educationalRequest)], msg:"EducationalRequest created successfully", code: 200);
        } catch (\Exception $e) {
            return $this->responseError(data: ['error' => $e->getMessage()], msg: "Failed to create EducationalRequest", code: 500);
        }
    }
}
