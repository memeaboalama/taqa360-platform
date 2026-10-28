<?php

namespace App\Domain\Project\Actions\Task;

use App\Domain\Project\Migrations\TaskNoteRepository;
use Carbon\Carbon;
use Domain\Project\Models\TaskNote;

class UpdateTaskNoteAction
{
    protected TaskNoteRepository $taskNoteRepository;

    public function __construct(TaskNoteRepository $taskNoteRepository)
    {
        $this->taskNoteRepository = $taskNoteRepository;
    }

    public function execute(TaskNote $taskNote, array $data): TaskNote
    {
        return $this->taskNoteRepository->update($taskNote->id,[
            'content' => $data['content'],
            'modified_at' => Carbon::now(),
        ]);
    }
}
