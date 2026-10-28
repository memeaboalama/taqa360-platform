<?php

namespace App\Application\Admin\Controllers\Api\Edu;

use App\Application\Api\Requests\Edu\Skill\StoreSkillRequest;
use App\Application\Api\Requests\Edu\Skill\UpdateSkillRequest;
use App\Domain\AppBase\Actions\Edu\Skill\CreateSkillAction;
use App\Domain\AppBase\Actions\Edu\Skill\UpdateSkillAction;
use App\Domain\AppBase\Resources\Edu\SkillResource;
use App\Http\Controllers\Controller;
use App\Support\Enum\StatusEnum;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\AppBase\Models\Edu\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $skills = Skill::paginate($request->get('per_page', 15));
        return $this->responseData([
            'skills' => SkillResource::collection($skills)
        ], 200, 'Skills retrieved successfully');
    }

    public function show(Skill $skill): JsonResponse
    {
        return $this->responseData([
            'skill' => SkillResource::make($skill)
        ], 200, 'Skill retrieved successfully');
    }

    public function store(StoreSkillRequest $request, CreateSkillAction $createSkillAction): JsonResponse
    {
        $skill = $createSkillAction->execute($request->validated());
        return $this->responseData([
            'skill' => SkillResource::make($skill)
        ], 201, 'Skill created successfully');
    }

    public function update(UpdateSkillRequest $request, Skill $skill, UpdateSkillAction $updateSkillAction): JsonResponse
    {
        $skill = $updateSkillAction->execute($skill, $request->validated());
        return $this->responseData([
            'skill' => SkillResource::make($skill)
        ], 200, 'Skill updated successfully');
    }

    public function destroy(Skill $skill): JsonResponse
    {
        $skill->delete();
        return $this->responseData([], 200, 'Skill deleted successfully');
    }

    // public function statusToggle(Skill $skill): JsonResponse
    // {
    //     $skill->status = !$skill->status;
    //     $skill->save();
    //     return $this->responseData([
    //         'skill' => SkillResource::make($skill)
    //     ], 200, 'Skill status toggled successfully');
    // }

    public function statusToggle(Skill $skill): JsonResponse
    {
        $skill->status = $skill->status === StatusEnum::ACTIVE
            ? StatusEnum::INACTIVE
            : StatusEnum::ACTIVE;

        $skill->save();

        return $this->responseData([
            'skill' => SkillResource::make($skill)
        ], 200, 'Skill status toggled successfully');
    }

}
