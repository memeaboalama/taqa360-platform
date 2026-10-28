<?php

namespace App\Application\Educator\Controllers\Api\Profile;

use App\Application\Educator\Requests\Profile\AcademicMajorRequest;
use App\Domain\Educator\Resource\Profile\AcademicMajorResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Domain\Educator\Actions\Profile\UpdateAcademicMajorAction;
use Domain\Educator\Models\EducatorAcademicMajor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AcademicMajorController extends Controller
{
    use ApiResponseTraitV2;

    public function fetch(Request $request): JsonResponse
    {
        $majors = $request->user()->academicMajors()->with(['certificateFile','approvedMajor'])->get();
        return $this->responseData(['academicMajor' => AcademicMajorResource::collection($majors)]);
    }

    public function store(AcademicMajorRequest $request, UpdateAcademicMajorAction $action)
    {
        $educator = $request->user();
        $upload = $request->file('certificate');
        $major = $action->handle($educator, $request->validated(), null, $upload);
        return $this->responseData(['academicMajor' => AcademicMajorResource::make($major)]);
    }

    public function update(AcademicMajorRequest $request,EducatorAcademicMajor $educatorMajor,  UpdateAcademicMajorAction $action)
    {
        $educator = $request->user();
        $upload = $request->file('certificate');
        $major = $action->handle($educator, $request->validated(), $educatorMajor->id, $upload);

        return $this->responseData(['academicMajor' => AcademicMajorResource::make($major)]);
    }


    public function destroy(EducatorAcademicMajor $educatorMajor): JsonResponse
    {
        if ($educatorMajor) {
            if ($educatorMajor->certificateFile) {
                $educatorMajor->certificateFile->delete();
            }
            $educatorMajor->delete();
        }

        return $this->responseData(msg:'AcademicMajor deleted successfully');
    }
}
