<?php

namespace App\Domain\AppBase\Actions\Edu\Skill;

use Domain\AppBase\Models\Edu\Skill;

class UpdateSkillAction
{
    public function execute(Skill $skill, array $data): Skill
    {
        $skill->update($data);
        return $skill;
    }
}
