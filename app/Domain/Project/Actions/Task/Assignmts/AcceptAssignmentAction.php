<?php

namespace App\Domain\Project\Actions\Task\Assignmts;

use App\Application\Project\Enums\AssignmentStatus;
use App\Domain\Project\Repositories\AssignmentRepository;
use Domain\Project\Models\Assignment;

class AcceptAssignmentAction
{
    public function __construct(protected AssignmentRepository $assignmentRepository){}

    /**
     * Admin-side acceptance of an assignment.
     *
     * Differs from the educator flow (AssignmentController@acceptInvitation):
     * the admin may accept regardless of who it is assigned to, and may attach
     * agreement notes.
     */
    public function handle(
        Assignment $assignment,
        ?string $agreementNotes = null,
        ?string $rejectionReason = null
    ): Assignment {
        if (in_array($assignment->status, [AssignmentStatus::CANCELLED, AssignmentStatus::COMPLETED], true)) {
            throw new \Exception('A cancelled or completed assignment cannot be accepted.');
        }

        $data = [
            'status' => AssignmentStatus::ACCEPTED,
            'accepted_at' => $assignment->accepted_at ?? now(),
        ];

        if ($agreementNotes !== null && $agreementNotes !== '') {
            $data['agreement_notes'] = $agreementNotes;
        }

        // The reason box is editable whatever the selected status is, so it has
        // to be accepted here too - otherwise accepting silently drops it.
        if ($rejectionReason !== null && $rejectionReason !== '') {
            $data['rejection_reason'] = $rejectionReason;
        }

        return $this->assignmentRepository->update($assignment->id, $data);
    }
}
