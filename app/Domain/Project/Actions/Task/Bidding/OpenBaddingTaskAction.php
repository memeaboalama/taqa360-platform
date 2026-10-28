<?php

namespace App\Domain\Project\Actions\Task\Bidding;

use App\Application\Project\Enums\BidStatus;
use Domain\Project\Models\Task;
use Domain\Project\Repositories\TaskRepository;

class OpenBaddingTaskAction
{
    public function __construct(protected TaskRepository $taskRepository){}

    public function handle(Task $task): Task
    {
        $task = $this->taskRepository->update($task->id, [
            'is_public' => true,
            'bidding_status' => "OPEN",
        ]);
        // event(new OpneBidingTask($subTask));
        return $task;
    }
}
