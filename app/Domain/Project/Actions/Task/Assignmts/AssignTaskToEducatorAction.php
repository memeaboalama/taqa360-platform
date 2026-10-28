<?php

namespace App\Domain\Project\Actions\Task\Assignmts;

use App\Application\Project\Enums\AssignmentStatus;
use App\Domain\Project\Repositories\AssignmentRepository;
use Domain\Project\Models\Task;
use Domain\Educator\Models\Educator;
use Domain\Admin\Models\Admin;
use Illuminate\Support\Facades\DB;
use Exception;

class AssignTaskToEducatorAction
{
    public function __construct(
        protected AssignmentRepository $assignmentRepository
    ) {}

    public function handle(Task $task, Educator $educator, array $data)
    {
        $admin = Admin::first();
        $assignment = $this->assignmentRepository->findByCols([
            'task_id'     => $task->id,
            'assigned_to' => $educator->id,
        ]);

        if ($assignment && $assignment->status !== AssignmentStatus::CANCELLED) {
            throw new Exception('This educator is already assigned to this task.');
        }

        return DB::transaction(function () use ($assignment, $task, $educator, $admin, $data) {

            if ($assignment && $assignment->status === AssignmentStatus::CANCELLED) {
                return $this->assignmentRepository->update($assignment->id, [
                    'assigned_by'      => $admin->id,
                    'amount'           => $data['amount'] ?? ($assignment->amount ?? 0),
                    'bid_id'           => $data['bid_id'] ?? $assignment->bid_id,
                    'assigned_at'      => now(),
                    'status'           => AssignmentStatus::PENDING,
                ]);
            }

            return $this->assignmentRepository->store([
                'task_id'         => $task->id,
                'assigned_to'     => $educator->id,
                'assigned_by'     => $admin->id,
                'assigned_type'   => $data['assigned_type'],
                'bid_id'          => $data['bid_id'] ?? null,
                'amount'          => $data['amount'] ?? 0,
                'agreement_notes' => $data['agreement_notes'] ?? null,
                'assigned_at'     => now(),
                'status'          => AssignmentStatus::PENDING,
            ]);
        });
    }
}
