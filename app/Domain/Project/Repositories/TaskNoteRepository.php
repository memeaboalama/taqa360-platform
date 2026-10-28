<?php

namespace App\Domain\Project\Migrations;

use Domain\Project\Models\TaskNote;
use App\Support\Repositories\Main\EloquentMainRepository;

class TaskNoteRepository extends EloquentMainRepository
{
    public function __construct(TaskNote $model)
    {
        parent::__construct($model);
    }
}
