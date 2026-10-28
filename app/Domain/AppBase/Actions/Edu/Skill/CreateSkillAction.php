<?php

namespace App\Domain\AppBase\Actions\Edu\Skill;

use Domain\AppBase\Models\Edu\Skill;

class CreateSkillAction
{
    public function execute(array $data): Skill
    {
        return Skill::create($data);
    }
}
