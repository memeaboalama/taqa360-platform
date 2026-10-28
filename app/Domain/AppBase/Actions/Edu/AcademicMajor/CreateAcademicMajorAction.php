<?php

namespace App\Domain\AppBase\Actions\Edu\AcademicMajor;

use Domain\AppBase\Models\Edu\AcademicMajor;

class CreateAcademicMajorAction
{
    public function handle(array $data): AcademicMajor
    {
        return AcademicMajor::create($data);
    }
}
