<?php

namespace App\Domain\Project\Repositories;

use App\Application\Project\Enums\AssignmentStatus;
use Domain\Project\Models\Workflow;
use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\Admin\Models\Admin;
use Domain\Project\Models\Assignment;

class AssignmentRepository extends EloquentMainRepository
{
    public function __construct(Assignment $model)
    {
        parent::__construct($model);
    }

    public function createAssignment(Workflow $workflow, array $data): Assignment
    {
        return $workflow->assignment()->create($data);
    }

    public function assignEducator(Assignment $assignment, int $educatorId, Admin $admin): Assignment
    {
        return tap($assignment)->update([
            'assigned_to' => $educatorId,
            'assigned_by' => $admin->id,
            'assigned_at' => now(),
            'status' => AssignmentStatus::PENDING
        ]);
    }

    public function completeAssignment(Assignment $assignment): Assignment
    {
        return tap($assignment)->update([
            'status' => AssignmentStatus::COMPLETED,
            'completed_at' => now()
        ]);
    }
}
