<?php

namespace Domain\Project\Actions\Task;

use Domain\Project\Repositories\TaskRepository;
use Domain\Project\Models\Task;
use Illuminate\Support\Facades\Validator;

class UpdateTaskAction
{
    public function __construct(protected TaskRepository $taskRepository){}

    public function handle(int $taskId, array $taskData): Task
    {
        $task = $this->taskRepository->updateTask($taskId, $taskData);
        // event(new TaskUpdated($task));
        return $task;
    }
}
