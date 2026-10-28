<?php

namespace App\Domain\Project\Actions\Task\Assignmts;

use App\Application\Project\Enums\AssignmentStatus;
use App\Domain\Project\Repositories\AssignmentRepository;
use Domain\Project\Models\Assignment;

class RejectAssignmentAction
{
    public function __construct(protected AssignmentRepository $assignmentRepository){}

    /**
     * Admin-side rejection. Cancelling is a separate flow
     * (TaskController@cancelAssignment -> AssignmentStatus::CANCELLED).
     */
    public function handle(
        Assignment $assignment,
        ?string $reason = null,
        ?string $agreementNotes = null
    ): Assignment {
        if (in_array($assignment->status, [AssignmentStatus::CANCELLED, AssignmentStatus::COMPLETED], true)) {
            throw new \Exception('A cancelled or completed assignment cannot be rejected.');
        }

        $data = ['status' => AssignmentStatus::REJECTED];

        // Own column: agreement_notes is used for the acceptance message, so
        // storing the rejection reason there made the two modal fields
        // overwrite each other.
        if ($reason !== null && $reason !== '') {
            $data['rejection_reason'] = $reason;
        }

        if ($agreementNotes !== null && $agreementNotes !== '') {
            $data['agreement_notes'] = $agreementNotes;
        }

        return $this->assignmentRepository->update($assignment->id, $data);
    }
}
