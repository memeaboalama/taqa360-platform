<?php

namespace Domain\Project\Actions\Task;

use App\Application\Project\Enums\ProjectStatus;
use Domain\Project\Models\Task;
use Domain\Project\Repositories\TaskRepository;

class TaskChangeStatusAction
{
    public function __construct(protected TaskRepository $taskRepository){}

    public function handle(Task $task, array $taskData): Task
    {
        $currentStatus = $task->status instanceof ProjectStatus
            ? $task->status
            : ProjectStatus::tryFrom((string) $task->status);
        $nextStatus = ProjectStatus::tryFrom((string) ($taskData['status'] ?? ''));
        $allowedStatuses = match ($currentStatus) {
            ProjectStatus::PENDING => [ProjectStatus::IN_PROGRESS],
            ProjectStatus::IN_PROGRESS => [ProjectStatus::COMPLETED],
            default => [],
        };

        if ($nextStatus === null || ! in_array($nextStatus, $allowedStatuses, true)) {
            throw new \DomainException('This task status transition is not allowed.');
        }

        $updates = ['status' => $nextStatus->value];
        if ($nextStatus === ProjectStatus::COMPLETED) {
            $updates['completed_at'] = $task->completed_at ?? now();
        }

        $this->taskRepository->update($task->id, $updates);
        // event(new TaskUpdated($task));
        return $task->fresh();
    }
}
