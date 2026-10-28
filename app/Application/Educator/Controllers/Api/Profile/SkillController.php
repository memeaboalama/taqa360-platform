<?php

namespace App\Application\Educator\Controllers\Api\Profile;

use App\Application\Educator\Requests\Profile\SkillRequest;
use App\Domain\Educator\Resource\Profile\EducatorSkillResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Domain\Educator\Actions\Profile\UpdateSkillsAction;
use Domain\Educator\Models\EducatorSkill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    use ApiResponseTraitV2;

    public function fetch(Request $request)
    {
        $educator = $request->user();
        $skills = $educator->skills()->with('approvedSkill')->get();
        return $this->responseData(['skills'=> EducatorSkillResource::collection($skills)]);
    }

    public function store(SkillRequest $request, UpdateSkillsAction $action)
    {
        $educator = $request->user();
        $action->handle($educator, $request->validated());
        return $this->responseData(['skills' => EducatorSkillResource::collection($educator->skills()->with('approvedSkill')->get())], msg:['message' => 'Skill added successfully']);
    }

    public function update(SkillRequest $request, EducatorSkill $skill, UpdateSkillsAction $action)
    {
        $educator = $request->user();
        $data = $request->validated() + ['id'=>$skill->id];
        $action->handle($educator, $data);
        return $this->responseData(['skills' => EducatorSkillResource::collection($educator->skills()->with('approvedSkill')->get())], msg:['message' => 'Skill updated successfully']);
    }

    public function destroy(EducatorSkill $skill, UpdateSkillsAction $action)
    {
        $educator = auth()->user();
        $action->delete($educator, $skill->id);
        return $this->responseData(msg:'Skill deleted successfully');
    }
}
