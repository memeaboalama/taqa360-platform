<?php

namespace App\Domain\Project\Actions\Task\Assignmts;

use App\Application\Project\Enums\AssignmentStatus;
use App\Domain\Project\Repositories\AssignmentRepository;
use Domain\Project\Models\Assignment;

class UpdateAssignmentStatusAction
{
    public function __construct(protected AssignmentRepository $assignmentRepository){}

    public function handle(
        Assignment $assignment,
        AssignmentStatus $status,
        ?string $agreementNotes = null,
        ?string $rejectionReason = null
    ): Assignment {
        if ($assignment->status === AssignmentStatus::CANCELLED && $status !== AssignmentStatus::CANCELLED) {
            throw new \Exception('A cancelled assignment cannot change status.');
        }

        $data = ['status' => $status];

        // keep accepted_at consistent with the status instead of letting the
        // admin set a historical date by hand
        if ($status === AssignmentStatus::ACCEPTED) {
            $data['accepted_at'] = $assignment->accepted_at ?? now();
        }

        if ($status === AssignmentStatus::COMPLETED) {
            $data['completed_at'] = $assignment->completed_at ?? now();
        }

        if ($agreementNotes !== null && $agreementNotes !== '') {
            $data['agreement_notes'] = $agreementNotes;
        }

        // separate column, see RejectAssignmentAction
        if ($rejectionReason !== null && $rejectionReason !== '') {
            $data['rejection_reason'] = $rejectionReason;
        }

        return $this->assignmentRepository->update($assignment->id, $data);
    }
}
