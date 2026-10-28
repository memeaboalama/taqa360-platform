<?php

namespace App\Domain\Project\Actions\Workflow;

use App\Application\Project\Enums\WorkflowStatus;
use App\Application\Project\Enums\WorkflowType;
use Domain\Project\Models\Workflow;
use Domain\Admin\Models\Admin;
use Domain\Project\Repositories\WorkflowRepository;
use Illuminate\Database\Eloquent\Model;

class CreateWorkflowAction
{
    public function __construct(protected WorkflowRepository $workflowRepository){}

    public function handle(Admin $admin, Model $workable, array $workflowData): Workflow
    {
        $type = $workflowData['type'] ?? WorkflowType::from($workflowData['type']);
        $status = ($type === WorkflowType::PUBLIC_TENDER)
            ? WorkflowStatus::OPEN_FOR_BIDS
            : WorkflowStatus::DRAFT;

        $attributes = array_merge($workflowData, [
            'status' => $status,
            'created_by' => $admin->id,
        ]);

        $workflow = $workable->workflows()->create($attributes);
        // event(new \App\Domain\Workflow\Events\WorkflowCreated($workflow));

        return $workflow;
    }
}
