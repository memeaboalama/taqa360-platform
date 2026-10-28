<?php

namespace App\Domain\Project\Actions\Bid;

use App\Application\Project\Enums\BidStatus;
use App\Domain\Project\Repositories\BidTaskRepository;
use Domain\Project\Models\BidTask;

class RejectBidTaskAction
{
    public function __construct(protected BidTaskRepository $bidTaskRepository){}

    public function handle(BidTask $bidTask, array $data, bool $assignment = false)
    {
        $bidTask = $this->bidTaskRepository->update($bidTask->id, [
            'status' => BidStatus::REJECTED,
            'rejection_reason' => $data['rejection_reason'] ?? null,
        ]);

        // event(new BidTaskAccepted($bidTask));
        return $bidTask;
    }
}
