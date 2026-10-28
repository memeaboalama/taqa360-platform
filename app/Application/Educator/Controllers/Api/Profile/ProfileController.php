<?php

namespace App\Application\Educator\Controllers\Api\Profile;

use App\Application\Educator\Requests\Profile\StoreAcademicMajorRequest;
use App\Application\Educator\Requests\Profile\BasicInfoRequest;
use App\Domain\Educator\Resource\EducatorResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Domain\Educator\Actions\Profile\UpdateAcademicMajorAction;
use Domain\Educator\Actions\Profile\UpdateBasicInfoAction;
use Domain\Educator\Models\EducatorAcademicMajor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use ApiResponseTraitV2;

    public function getBasicInfo(Request $request): JsonResponse
    {
        $educator = $request->user()->load('profile.nationality', 'profile.country', 'profile.city', 'files');
        return $this->responseData(['educator' => new EducatorResource($educator)]);
    }

    public function updateBasicInfo(BasicInfoRequest $request, UpdateBasicInfoAction $action): JsonResponse
    {
        $educator = $action->handle(
            $request->user(),
            $request->validated(),
            $request->file('identityImageFront'),
            $request->file('identityImageBack')
        );
        return $this->responseData(['educator' => new EducatorResource($educator)], 200, __('messages.updated_successfully'));
    }

    public function getAcademicMajors(Request $request): JsonResponse
    {
        $profiles = $request->user()->academicMajors()->with(['certificateFile', 'approvedMajor'])->get();
        return $this->responseData(['profiles' => $profiles]);
    }

    public function storeAcademicProfile(StoreAcademicMajorRequest $request, UpdateAcademicMajorAction $action): JsonResponse
    {
        $profile = $action->handle($request->user(),$request->validated(),null,$request->file('upload'));
        return $this->responseData(['profile' => $profile], 201, __('messages.created_successfully'));
    }

    public function updateAcademicProfile(StoreAcademicMajorRequest $request, EducatorAcademicMajor $profile, UpdateAcademicMajorAction $action): JsonResponse
    {
        $this->authorize('update', $profile);

        $updatedProfile = $action->handle(
            $request->user(),
            $request->validated(),
            $profile->id,
            $request->file('upload')
        );
        return $this->responseData(['profile' => $updatedProfile], 200, __('messages.updated_successfully'));
    }

    public function destroyAcademicProfile(EducatorAcademicMajor $profile): JsonResponse
    {
        $this->authorize('delete', $profile);
        $profile->delete();
        return $this->responseData([], 200, __('messages.deleted_successfully'));
    }
}
