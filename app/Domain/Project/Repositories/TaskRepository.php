<?php

namespace Domain\Project\Repositories;

use Domain\Project\Models\Project;
use Domain\Project\Models\Task;
use App\Support\Repositories\Main\EloquentMainRepository;

class TaskRepository extends EloquentMainRepository
{
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }

    public function createTaskForProject(Project $project, array $data): Task
    {
        return $project->tasks()->create($data);
    }

    public function updateTask(int $taskId, array $data): Task
    {
        $task = $this->modal->findOrFail($taskId);
        $task->update($data);
        return $task;
    }

    public function deleteTask(int $taskId): bool
    {
        $task = $this->modal->findOrFail($taskId);
        return $task->delete();
    }

    public function storeAttachment(Task $task, array $data): Task {
        $task->attachments()->create($data);
        return $task;
    }
}
