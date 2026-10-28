<?php

namespace Domain\Project\Actions\Task\SubTask;

use Domain\Project\Models\Task;
use Domain\Project\Repositories\SubTaskRepository;

class FetchSubTaskAction
{
    public function __construct(protected SubTaskRepository $subTaskRepository){}

    public function handle(Task $task)
    {
        return $this->subTaskRepository->all(condition:['task_id', $task->id], relations: ['attachments']);
    }
}
