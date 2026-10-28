<?php

namespace App\Domain\Project\Actions\Bid;

use App\Application\Project\Enums\AssignmentStatus;
use App\Application\Project\Enums\AssignmentType;
use App\Application\Project\Enums\BidStatus;
use App\Domain\Project\Repositories\AssignmentRepository;
use App\Domain\Project\Repositories\BidTaskRepository;
use Domain\Project\Models\Task;
use Domain\Educator\Models\Educator;
use Domain\Admin\Models\Admin;
use Domain\Project\Models\BidTask;
use Illuminate\Support\Facades\DB;
use Exception;

class DirectAssignEducatorAction
{
    public function __construct(
        protected AssignmentRepository $assignmentRepository,
        protected BidTaskRepository $bidTaskRepository
    ) {}

    public function handle(BidTask $bidTask, array $data)
    {
        $admin = Admin::first();
        $educator = Educator::where('uuid', $data['educator_id'])->firstOrFail();

        if(!$educator) throw new Exception('Educator not found');

        $assignment = $this->assignmentRepository->findByCols([
            'task_id'     => $bidTask->task_id,
            'assigned_to' => $educator->id,
        ]);

        if ($assignment && $assignment->status !== AssignmentStatus::CANCELLED) {
            throw new Exception('This educator is already assigned to this task.');
        }

        return DB::transaction(function () use ($assignment, $bidTask, $educator, $admin, $data) {

            $bidTask = $this->bidTaskRepository->update($bidTask->id, [
                'status' => BidStatus::ASSIGNED,
                'approval_message' => $data['agreement_notes'] ?? null,
            ]);

            if ($assignment && $assignment->status === AssignmentStatus::CANCELLED) {
                return $this->assignmentRepository->update($assignment->id, [
                    'assigned_by'      => $admin->id,
                    'amount'           => $bidTask->amount,
                    'bid_id'           => $bidTask->bid_id,
                    'assigned_at'      => now(),
                    'status'           => AssignmentStatus::PENDING,
                ]);
            }

            return $this->assignmentRepository->store([
                'task_id'         => $bidTask->task_id,
                'assigned_to'     => $educator->id,
                'assigned_by'     => $admin->id,
                'assigned_type'   => AssignmentType::FROM_BID,
                'bid_id'          => $bidTask->bid_id,
                'amount'          => $bidTask->amount,
                'agreement_notes' => $data['agreement_notes'] ?? null,
                'assigned_at'     => now(),
                'status'          => AssignmentStatus::PENDING,
            ]);
        });
    }
}
