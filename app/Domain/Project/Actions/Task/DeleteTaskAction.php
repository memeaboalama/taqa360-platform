<?php

namespace Domain\Project\Actions\Task;

use Domain\Project\Models\Task;
use Domain\Project\Repositories\TaskRepository;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

class DeleteTaskAction
{
    public function __construct(protected TaskRepository $taskRepository){}

    public function handle(Task $task): Bool
    {
        $taskDeleted = $this->taskRepository->destroy($task->id);
        // event(new TaskDeleted($task));
        return $taskDeleted;
    }
}
