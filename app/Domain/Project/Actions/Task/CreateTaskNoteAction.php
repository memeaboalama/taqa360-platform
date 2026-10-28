<?php

namespace App\Domain\Project\Actions\Task;

use App\Domain\Project\Migrations\TaskNoteRepository;
use Domain\Project\Models\Task;
use Domain\Project\Models\TaskNote;

class CreateTaskNoteAction
{
    protected TaskNoteRepository $taskNoteRepository;

    public function __construct(TaskNoteRepository $taskNoteRepository)
    {
        $this->taskNoteRepository = $taskNoteRepository;
    }

    public function execute(Task $task, array $data, string $userId): TaskNote
    {
        return $this->taskNoteRepository->store([
            'task_id' => $task->id,
            'content' => $data['content'],
            'creatable_type' => get_class(auth()->user()),
            'creatable_id' => $userId,
        ]);
    }
}
