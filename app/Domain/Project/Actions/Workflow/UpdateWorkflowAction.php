<?php

namespace App\Domain\Project\Actions\Workflow;

use App\Application\Project\Enums\WorkflowStatus;
use Domain\Project\Models\Workflow;
use Domain\Project\Repositories\WorkflowRepository;
use Illuminate\Validation\ValidationException;

class UpdateWorkflowAction
{
    public function __construct(protected WorkflowRepository $workflowRepository){}

    public function handle(Workflow $workflow, array $workflowData): Workflow
    {
        if (!in_array($workflow->status, [WorkflowStatus::DRAFT,WorkflowStatus::PENDING_ASSIGNMENT])) {
            throw new \Exception("Cannot update a workflow that is already active or closed.");
        }

        $this->workflowRepository->update($workflow->id, $workflowData);
        // event(new WorkflowUpdated($workflow));
        return $workflow->fresh();
    }
}
