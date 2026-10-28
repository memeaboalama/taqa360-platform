<?php

namespace App\Domain\Project\Actions\Task\Bidding;

use App\Application\Project\Enums\BidStatus;
use Domain\Project\Models\Task;
use Domain\Project\Repositories\TaskRepository;

class CloseBaddingTaskAction
{
    public function __construct(protected TaskRepository $taskRepository){}

    public function handle(Task $task): Task
    {
        $task = $this->taskRepository->update($task->id, [
            'is_public' => true,
            'bidding_status' => "CLOSED",
        ]);
        // event(new CloseBidingTask($subTask));
        return $task;
    }
}
