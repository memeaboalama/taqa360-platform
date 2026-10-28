<?php

namespace App\Domain\Project\Actions\Workflow;

use App\Application\Project\Enums\WorkflowStatus;
use App\Application\Project\Enums\WorkflowType;
use Domain\Project\Models\Workflow;
use Domain\Project\Repositories\WorkflowRepository;

class PublishTenderAction
{
    public function __construct(protected WorkflowRepository $workflowRepository){}

    public function handle(Workflow $workflow): Workflow
    {
        if ($workflow->type !== WorkflowType::PUBLIC_TENDER) {
            throw new \Exception('This action is only for public tenders.');
        }
        if ($workflow->status !== WorkflowStatus::DRAFT) {
            throw new \Exception('Only draft tenders can be published.');
        }

        $this->workflowRepository->update($workflow->id, ['status' => WorkflowStatus::OPEN_FOR_BIDS]);
        // event(new TenderPublished($workflow));

        return $workflow->fresh();
    }
}
