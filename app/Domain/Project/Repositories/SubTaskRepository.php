<?php

namespace Domain\Project\Repositories;

use Domain\Project\Models\Project;
use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\Admin\Models\Admin;
use Domain\Educator\Models\Educator;
use Domain\Project\Models\SubTask;
use Illuminate\Support\Facades\Log;

class SubTaskRepository extends EloquentMainRepository
{
    public function __construct(SubTask $model)
    {
        parent::__construct($model);
    }

    public function createSubTask(Admin|Educator $createor, array $data): SubTask {
        $subTask = $createor->subTasks()->create($data);
        return $subTask;
    }

    public function storeAttachment(SubTask $subTask, array $subTaskData): SubTask {
        $subTask->attachments()->create($subTaskData);
        return $subTask;
    }
}
