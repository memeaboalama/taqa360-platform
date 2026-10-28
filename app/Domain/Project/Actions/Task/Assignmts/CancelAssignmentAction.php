<?php

namespace App\Domain\Project\Actions\Task\Assignmts;

use App\Application\Project\Enums\AssignmentStatus;
use App\Domain\Project\Repositories\AssignmentRepository;

class CancelAssignmentAction
{
    public function __construct(protected AssignmentRepository $assignmentRepository){}

    /**
     * Admin-side cancel = withdraw the assignment entirely, so the specialist no
     * longer has any relationship with the task. That is only meaningful while
     * the specialist has not decided yet: once they accepted or rejected, the
     * relationship is already settled and cancelling it would rewrite history.
     */
    public function handle(?string $reason = null, $assignmentId = null)
    {
        $assignment = $this->assignmentRepository->findById($assignmentId);

        if (!$assignment) {
            throw new \Exception('Assignment not found.');
        }

        $current = $assignment->status instanceof \BackedEnum ? $assignment->status->value : $assignment->status;

        if ($current === AssignmentStatus::ACCEPTED->value) {
            throw new \Exception(
                'لا يمكن إلغاء التعيين بعد قبول المتخصص له. يمكن رفضه بدلاً من ذلك.'
            );
        }

        if ($current === AssignmentStatus::REJECTED->value) {
            throw new \Exception('رفض المتخصص هذا التعيين بالفعل، ولا يمكن إلغاؤه.');
        }

        if ($current === AssignmentStatus::COMPLETED->value) {
            throw new \Exception('لا يمكن إلغاء تعيين مكتمل.');
        }

        if ($current === AssignmentStatus::CANCELLED->value) {
            throw new \Exception('هذا التعيين ملغى مسبقاً.');
        }

        // Soft delete, not just a status change: cancelling must remove the
        // specialist from the task, and a lingering "cancelled" row kept showing
        // up in the admin list as if the specialist had rejected it. The row is
        // kept (trashed) so the decision stays auditable, and re-assigning the
        // same specialist later creates a fresh row.
        $assignment->status = AssignmentStatus::CANCELLED;
        $assignment->save();
        $assignment->delete();

        return $assignment;
    }
}
