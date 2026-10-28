<?php

namespace Domain\Project\Actions\Task\SubTask;

use Domain\Admin\Models\Admin;
use Domain\Educator\Models\Educator;
use Domain\Project\Models\SubTask;
use Domain\Project\Models\Task;
use Domain\Project\Repositories\SubTaskRepository;

class CreateSubTaskAction
{
    public function __construct(protected SubTaskRepository $subTaskRepository){}

    public function handle(Admin|Educator $createor,array $subTaskData): SubTask
    {
        $subTask = $this->subTaskRepository->createSubTask($createor, $subTaskData);
        // event(new SubTaskCreated($subTask));
        return $subTask;
    }
}
