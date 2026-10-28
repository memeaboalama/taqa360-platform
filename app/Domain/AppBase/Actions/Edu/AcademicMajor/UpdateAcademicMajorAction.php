<?php

namespace App\Domain\AppBase\Actions\Edu\AcademicMajor;

use Domain\AppBase\Models\Edu\AcademicMajor;

class UpdateAcademicMajorAction
{
    public function handle(AcademicMajor $academicMajor, array $data): AcademicMajor
    {
        $academicMajor->update($data);
        return $academicMajor;
    }
}
