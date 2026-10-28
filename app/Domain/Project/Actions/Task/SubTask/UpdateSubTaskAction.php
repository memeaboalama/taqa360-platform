<?php

namespace Domain\Project\Actions\Task\SubTask;

use Domain\Project\Models\SubTask;
use Domain\Project\Repositories\TaskRepository;
use Domain\Project\Models\Task;
use Domain\Project\Repositories\SubTaskRepository;
use Illuminate\Support\Facades\Validator;

class UpdateSubTaskAction
{
    public function __construct(protected SubTaskRepository $subTaskRepository){}

    public function handle(int $subTaskId, array $subTaskData): SubTask
    {
        $subTask = $this->subTaskRepository->update($subTaskId, $subTaskData);
        // event(new SubTaskUpdated($subTask));
        return $subTask;
    }
}
