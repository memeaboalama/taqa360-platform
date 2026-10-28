<?php

namespace Application\Project\Controllers\Api;

use App\Application\Api\Http\Requests\Workflows\CreateWorkflowRequest;
use App\Application\Api\Http\Requests\Workflows\UpdateWorkflowRequest;
use App\Application\Api\Http\Requests\Workflows\AssignWorkflowRequest;
use App\Application\Api\Http\Requests\Workflows\CancelWorkflowRequest;
use App\Application\Api\Http\Resources\WorkflowResource;
use App\Application\Api\Http\Resources\AssignmentResource;
use App\Domain\Workflow\Actions\CreateWorkflowAction;
use App\Domain\Workflow\Actions\UpdateWorkflowAction;
use App\Domain\Workflow\Actions\PublishTenderAction;
use App\Domain\Workflow\Actions\CancelWorkflowAction;
use App\Domain\Workflow\Actions\AssignWorkflowToEducatorAction;
use App\Domain\Workflow\Actions\AcceptBidAction;
use App\Domain\Workflow\DataTransferObjects\WorkflowData;
use App\Domain\Workflow\Models\Workflow;
use App\Domain\Workflow\Models\Bid;
use App\Domain\Project\Models\Project;
use App\Domain\Project\Models\Task;
use Domain\Educator\Models\Educator;
use Illuminate\Http\JsonResponse;

class WorkflowController extends Controller
{
    public function store(CreateWorkflowRequest $request, CreateWorkflowAction $action): JsonResponse
    {
        $workable = $this->getWorkable($request->workable_type, $request->workable_id);
        $this->authorize('createWorkflow', $workable);

        $workflowData = WorkflowData::from($request->validated());
        $workflow = $action->handle($request->user(), $workable, $workflowData);

        return response()->json(new WorkflowResource($workflow), 201);
    }

    /**
     * Display a specific workflow.
     */
    public function show(Workflow $workflow): JsonResponse
    {
        $this->authorize('view', $workflow);
        return response()->json(new WorkflowResource($workflow->load(['workable', 'bids.educator', 'assignment.educator'])));
    }

    /**
     * Update a workflow's details (only if it's in a draft state).
     */
    public function update(UpdateWorkflowRequest $request, Workflow $workflow, UpdateWorkflowAction $action): JsonResponse
    {
        $this->authorize('update', $workflow);

        $workflowData = WorkflowData::from($request->validated());
        $updatedWorkflow = $action->handle($workflow, $workflowData);

        return response()->json(new WorkflowResource($updatedWorkflow));
    }

    /**
     * Publish a draft workflow as a public tender.
     */
    public function publish(Workflow $workflow, PublishTenderAction $action): JsonResponse
    {
        $this->authorize('update', $workflow);
        $publishedWorkflow = $action->handle($workflow);
        return response()->json(new WorkflowResource($publishedWorkflow));
    }

    /**
     * Directly assign a workflow to a specific educator.
     */
    public function assign(AssignWorkflowRequest $request, Workflow $workflow, AssignWorkflowToEducatorAction $action): JsonResponse
    {
        $this->authorize('assign', $workflow);

        $educator = Educator::findOrFail($request->educator_id);
        $assignment = $action->handle($request->user(), $workflow, $educator, $request->validated());

        return response()->json(new AssignmentResource($assignment), 201);
    }

    /**
     * Accept a bid for a public tender, which creates an assignment.
     */
    public function acceptBid(Workflow $workflow, Bid $bid, AcceptBidAction $action): JsonResponse
    {
        $this->authorize('acceptBid', $workflow);

        // Ensure the bid belongs to the workflow
        if ($bid->workflow_id !== $workflow->id) {
            return response()->json(['message' => 'This bid does not belong to the specified workflow.'], 422);
        }

        $assignment = $action->handle($request->user(), $bid);

        return response()->json(new AssignmentResource($assignment));
    }

    /**
     * Cancel a workflow before it's completed.
     */
    public function cancel(CancelWorkflowRequest $request, Workflow $workflow, CancelWorkflowAction $action): JsonResponse
    {
        $this->authorize('cancel', $workflow);

        $cancelledWorkflow = $action->handle($request->user(), $workflow, $request->reason);

        return response()->json(new WorkflowResource($cancelledWorkflow));
    }

    /**
     * Helper to resolve the workable model.
     */
    private function getWorkable(string $type, int $id): Model
    {
        if ($type === 'project') {
            return Project::findOrFail($id);
        }
        if ($type === 'task') {
            return Task::findOrFail($id);
        }
        throw new \InvalidArgumentException("Invalid workable type provided.");
    }
}
