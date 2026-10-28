<?php

namespace App\Domain\Project\Actions\Bid;

use App\Application\Project\Enums\WorkflowStatus;
use Domain\Project\Models\Workflow;
use Domain\Admin\Models\Admin;
use Domain\Project\Repositories\WorkflowRepository;

class CancelWorkflowAction
{
    public function __construct(protected WorkflowRepository $workflowRepository){}

    public function handle(Admin $admin, Workflow $workflow, string $reason): Workflow
    {
        if ($workflow->status === WorkflowStatus::IN_PROGRESS || $workflow->status === WorkflowStatus::COMPLETED) {
            throw new \Exception("Cannot cancel a workflow that is already in progress or completed.");
        }

        $this->workflowRepository->update($workflow->id, ['status' => WorkflowStatus::CANCELLED]);

        // $workflow->history()->create([]);
        // event(new WorkflowCancelled($workflow, $admin, $reason));

        return $workflow->fresh();
    }
}
