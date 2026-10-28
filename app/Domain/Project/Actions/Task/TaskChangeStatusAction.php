<?php

namespace Domain\Project\Actions\Task;

use Domain\Project\Models\Task;
use Domain\Project\Repositories\TaskRepository;

class TaskChangeStatusAction
{
    public function __construct(protected TaskRepository $taskRepository){}

    public function handle(Task $task, array $taskData): Task
    {
        $this->taskRepository->update($task->id, ['status' => $taskData['status']]);
        // event(new TaskUpdated($task));
        return $task->fresh();
    }
}
