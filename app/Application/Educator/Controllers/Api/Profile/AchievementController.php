<?php

namespace App\Application\Educator\Controllers\Api\Profile;

use App\Application\Educator\Requests\Profile\AchievementRequest;
use App\Domain\Educator\Resource\Profile\AchievementResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Domain\Educator\Actions\Profile\UpdateAchievementsAction;
use Domain\Educator\Models\EducatorAchievement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AchievementController extends Controller
{
    use ApiResponseTraitV2;

    public function fetch(Request $request): JsonResponse
    {
        $educator = $request->user()->load('achievements');
        return $this->responseData(['achievements' => AchievementResource::collection($educator->achievements)]);
    }

    public function store(AchievementRequest $request, UpdateAchievementsAction $action): JsonResponse
    {
        $educator = $request->user();
        $attachment = $request->file('attachment');
        $achievement = $action->handle($educator, $request->validated(), null, $attachment);

        return $this->responseData(data:['achievement' => AchievementResource::make($achievement)],msg:'Achievement stored successfully');
    }

    public function update(AchievementRequest $request, EducatorAchievement $achievement, UpdateAchievementsAction $action)
    {
        $educator = $request->user();
        $data = $request->validated();
        $attachment = $request->file('attachment');
        $achievement = $action->handle($educator, $data, $achievement->id, $attachment);

        return $this->responseData(data:['achievement' => AchievementResource::make($achievement)],msg:'Achievement stored successfully');
    }

    public function destroy(EducatorAchievement $achievement): JsonResponse
    {
        if ($achievement) {
            if ($achievement->file) {
                $achievement->file->delete();
            }
            $achievement->delete();
        }
        return $this->responseData(msg:'Achievement deleted successfully');
    }
}
