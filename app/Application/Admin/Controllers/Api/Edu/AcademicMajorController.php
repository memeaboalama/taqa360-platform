<?php

namespace App\Application\Admin\Controllers\Api\Edu;

use App\Application\Api\Requests\Edu\AcademicMajor\StoreAcademicMajorRequest;
use App\Application\Api\Requests\Edu\AcademicMajor\UpdateAcademicMajorRequest;
use App\Domain\AppBase\Actions\Edu\AcademicMajor\CreateAcademicMajorAction;
use App\Domain\AppBase\Actions\Edu\AcademicMajor\UpdateAcademicMajorAction;
use App\Domain\AppBase\Resources\Edu\AcademicMajorResource;
use App\Http\Controllers\Controller;
use App\Support\Enum\StatusEnum;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\AppBase\Models\Edu\AcademicMajor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcademicMajorController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $academicMajors = AcademicMajor::paginate($request->get('per_page', 15));
        return $this->responseData([
            'academic_majors' => AcademicMajorResource::collection($academicMajors)
        ], 200, 'Academic majors retrieved successfully');
    }

    public function show(AcademicMajor $academicMajor): JsonResponse
    {
        return $this->responseData([
            'academic_major' => AcademicMajorResource::make($academicMajor)
        ], 200, 'Academic major retrieved successfully');
    }

    public function store(StoreAcademicMajorRequest $request, CreateAcademicMajorAction $createAcademicMajorAction): JsonResponse
    {
        $academicMajor = $createAcademicMajorAction->handle($request->validated());
        $academicMajor->fresh();
        return $this->responseData([
            'academic_major' => AcademicMajorResource::make($academicMajor)
        ], 201, 'Academic major created successfully');
    }

    public function update(UpdateAcademicMajorRequest $request, AcademicMajor $academicMajor, UpdateAcademicMajorAction $updateAcademicMajorAction): JsonResponse
    {
        $academicMajor = $updateAcademicMajorAction->handle($academicMajor, $request->validated());
        return $this->responseData([
            'academic_major' => AcademicMajorResource::make($academicMajor)
        ], 200, 'Academic major updated successfully');
    }

    public function destroy(AcademicMajor $academicMajor): JsonResponse
    {
        $academicMajor->delete();
        return $this->responseData([], 200, 'Academic major deleted successfully');
    }

    public function statusToggle(AcademicMajor $academicMajor): JsonResponse
    {
        $academicMajor->status = $academicMajor->status === StatusEnum::ACTIVE
            ? StatusEnum::INACTIVE
            : StatusEnum::ACTIVE;

        $academicMajor->save();

        return $this->responseData([
            'academic_major' => AcademicMajorResource::make($academicMajor)
        ], 200, 'Academic major status toggled successfully');
    }
}
