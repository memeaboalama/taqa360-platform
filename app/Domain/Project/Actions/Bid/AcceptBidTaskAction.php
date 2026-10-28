<?php

namespace App\Domain\Project\Actions\Bid;

use App\Application\Project\Enums\BidStatus;
use App\Domain\Project\Repositories\BidTaskRepository;
use Domain\Project\Models\BidTask;

class AcceptBidTaskAction
{
    public function __construct(protected BidTaskRepository $bidTaskRepository){}

    public function handle(BidTask $bidTask, array $data, bool $assignment = false)
    {
        $bidTask = $this->bidTaskRepository->update($bidTask->id, [
            'status' => BidStatus::APPROVED,
            'approval_message' => $data['approval_message'] ?? null,
        ]);

        // event(new BidTaskAccepted($bidTask));
        return $bidTask;
    }
}
