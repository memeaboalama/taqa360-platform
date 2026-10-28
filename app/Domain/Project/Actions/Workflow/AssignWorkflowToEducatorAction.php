<?php

namespace App\Domain\Project\Actions\Workflow;

use App\Application\Project\Enums\AssignmentStatus;
use App\Application\Project\Enums\WorkflowStatus;
use App\Application\Project\Enums\WorkflowType;
use Domain\Project\Models\Assignment;
use Domain\Project\Models\Workflow;
use App\Domain\Project\Repositories\AssignmentRepository;
use Domain\Admin\Models\Admin;
use Domain\Educator\Models\Educator;
use Illuminate\Support\Facades\DB;

class AssignWorkflowToEducatorAction
{
    public function __construct(protected AssignmentRepository $assignmentRepository){}

    public function handle(Admin $admin, Workflow $workflow, Educator $educator, array $data): Assignment
    {
        if ($workflow->type !== WorkflowType::DIRECT_ASSIGNMENT) {
            throw new \Exception("This action is only for direct assignments.");
        }
        if ($workflow->assignment) {
            throw new \Exception("This workflow has already been assigned.");
        }

        return DB::transaction(function () use ($admin, $workflow, $educator, $data) {

            $assignmentData = [
                'educator_id' => $educator->id,
                'status' => AssignmentStatus::PENDING,
                'final_price' => $data['price'],
                'instructions' => $data['instructions'] ?? null,
            ];

            $assignment = $this->assignmentRepository->createAssignment($workflow, $assignmentData);

            $workflow->update(['status' => WorkflowStatus::IN_PROGRESS]);

            // event(new WorkflowAssigned($assignment));

            return $assignment;
        });
    }
}
