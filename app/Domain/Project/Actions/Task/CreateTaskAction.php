<?php

namespace Domain\Project\Actions\Task;

use Domain\Project\Models\Project;
use Domain\Project\Models\Task;
use Domain\Project\Repositories\TaskRepository;

class CreateTaskAction
{
    public function __construct(protected TaskRepository $taskRepository){}

    public function handle(array $taskData): Task
    {
        $taskData['created_by'] = 1;
        $project = Project::where('uuid', $taskData['project_id'])->firstOrFail();

        $task = $this->taskRepository->createTaskForProject($project, $taskData);
        // event(new TaskCreated($task));
        return $task;
    }
}
