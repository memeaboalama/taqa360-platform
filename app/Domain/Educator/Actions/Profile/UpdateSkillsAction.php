<?php

namespace Domain\Educator\Actions\Profile;

use Domain\AppBase\Models\Edu\Skill;
use Domain\Educator\Models\Educator;
use Illuminate\Support\Facades\DB;

class UpdateSkillsAction
{
    public function handle(Educator $educator, array $skillData): void
    {
        DB::transaction(function () use ($educator, $skillData) {
            $id = $skillData['id'] ?? null;
            $skillId = Skill::where('uuid', $skillData['skill_id'] ?? null)->value('id');
            $skillData['skill_id'] = $skillId;

            $data = [
                'skill_id' => $skillData['is_custom'] ? null : ($skillData['skill_id'] ?? null),
                'custom_name' => $skillData['is_custom'] ? ($skillData['custom_name'] ?? null) : null,
                'is_custom' => (bool)($skillData['is_custom'] ?? false),
                'level' => $skillData['level'] ?? 3,
            ];
            $educator->skills()->updateOrCreate(['id'=>$id], $data);
        });
    }

    public function delete(Educator $educator, int $id): void
    {
        $educator->skills()->find($id)?->delete();
    }
}
