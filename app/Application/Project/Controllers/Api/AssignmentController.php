<?php

namespace Application\Project\Controllers\Api;

use App\Application\Project\Enums\AssignmentStatus;
use App\Domain\Project\Actions\Task\Assignmts\AcceptAssignmentAction;
use App\Domain\Project\Actions\Task\Assignmts\RejectAssignmentAction;
use App\Domain\Project\Actions\Task\Assignmts\UpdateAssignmentStatusAction;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\Project\Models\Assignment;
use Domain\Project\Actions\Project\LogProjectActivityAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use BackedEnum;

class AssignmentController extends Controller
{
    use ApiResponseTrait;

    /**
     * Relations the response touches. AssignmentResource reads
     * $this->task->project->title unconditionally, so both must be eager loaded.
     */
    private const RELATIONS = ['task.project', 'educator'];

    /**
     * POST /api/v1/admin/assignments/{assignment}/accept
     */
    public function accept(Request $request, Assignment $assignment, AcceptAssignmentAction $acceptAssignmentAction, LogProjectActivityAction $logActivity): JsonResponse
    {
        try {
            $task = $assignment->task;
            $educatorName = $assignment->educator?->name;

            $assignment = $acceptAssignmentAction->handle(
                $assignment,
                $request->input('agreement_notes') ?? $request->input('agreementNotes'),
                $request->input('rejection_reason') ?? $request->input('rejectionReason')
            );

            $logActivity->forTask($task, LogProjectActivityAction::ASSIGNMENT_ACCEPTED, [
                'educator' => $educatorName,
                'title' => $task?->title,
            ]);

            return $this->responseData(data: $this->payload($assignment), msg: 'Assignment accepted successfully.');
        } catch (\Exception $e) {
            return $this->responseError(msg: $e->getMessage(), code: 400);
        }
    }

    /**
     * POST /api/v1/admin/assignments/{assignment}/reject
     */
    public function reject(Request $request, Assignment $assignment, RejectAssignmentAction $rejectAssignmentAction, LogProjectActivityAction $logActivity): JsonResponse
    {
        try {
            $task = $assignment->task;
            $educatorName = $assignment->educator?->name;
            $reason = $request->input('reason') ?: null;

            $assignment = $rejectAssignmentAction->handle(
                $assignment,
                $request->input('reason') ?? $request->input('rejection_reason') ?? $request->input('rejectionReason'),
                $request->input('agreement_notes') ?? $request->input('agreementNotes')
            );

            $logActivity->forTask($task, LogProjectActivityAction::ASSIGNMENT_REJECTED, [
                'educator' => $educatorName,
                'title' => $task?->title,
                'reason' => $reason,
            ]);

            return $this->responseData(data: $this->payload($assignment), msg: 'Assignment rejected successfully.');
        } catch (\Exception $e) {
            return $this->responseError(msg: $e->getMessage(), code: 400);
        }
    }

    /**
     * PATCH /api/v1/admin/assignments/{assignment}/status
     */
    public function updateStatus(Request $request, Assignment $assignment, UpdateAssignmentStatusAction $updateAssignmentStatusAction, LogProjectActivityAction $logActivity): JsonResponse
    {
        try {
            $status = $this->resolveStatus($request->input('status'));
            if ($status === null) {
                return $this->responseError(
                    msg: 'Invalid status. Expected one of: ' . implode(', ', AssignmentStatus::values()) . '.',
                    code: 422
                );
            }

            $task = $assignment->task;
            $educatorName = $assignment->educator?->name;
            $from = $assignment->status instanceof BackedEnum ? $assignment->status->value : (string) $assignment->status;

            $assignment = $updateAssignmentStatusAction->handle(
                $assignment,
                $status,
                $request->input('agreement_notes') ?? $request->input('agreementNotes'),
                $request->input('rejection_reason') ?? $request->input('rejectionReason')
            );

            if ($from !== $status->value) {
                $logActivity->forTask($task, LogProjectActivityAction::ASSIGNMENT_STATUS_CHANGED, [
                    'educator' => $educatorName,
                    'title' => $task?->title,
                    'from' => $from,
                    'to' => $status->value,
                ]);
            }

            return $this->responseData(data: $this->payload($assignment), msg: 'Assignment status updated successfully.');
        } catch (\Exception $e) {
            return $this->responseError(msg: $e->getMessage(), code: 400);
        }
    }

    /**
     * The admin SPA sends UPPERCASE enum values ('ACCEPTED'), the database
     * stores lowercase. Accept either so both sides keep working.
     */
    private function resolveStatus($value): ?AssignmentStatus
    {
        if ($value instanceof AssignmentStatus) {
            return $value;
        }

        if (!is_string($value) || $value === '') {
            return null;
        }

        return AssignmentStatus::tryFrom(strtolower($value));
    }

    /**
     * Shape expected by the admin SPA: it reads data.task_id directly to
     * invalidate its cache keys, so task_id must be the task UUID.
     */
    private function payload(Assignment $assignment): array
    {
        $assignment->loadMissing(self::RELATIONS);

        return [
            'id' => (string) $assignment->uuid,
            'task_id' => (string) $assignment->task?->uuid,
            'educatorId' => $assignment->assigned_to ? (string) $assignment->educator?->uuid : null,
            'type' => $assignment->assigned_type?->value,
            'status' => $assignment->status?->value,
            'amount' => $assignment->amount ? (float) $assignment->amount : null,
            'agreementNotes' => $assignment->agreement_notes,
            'rejectionReason' => $assignment->rejection_reason,
            'assigned_at' => $assignment->assigned_at?->toDateString(),
            'accepted_at' => $assignment->accepted_at?->toDateString(),
            'completed_at' => $assignment->completed_at?->toDateString(),
        ];
    }
}
