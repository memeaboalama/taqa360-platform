<?php

namespace Domain\Project\Resources\Project;

use App\Application\Project\Enums\AssignmentStatus;
use App\Application\Project\Enums\TaskStatus;
use Domain\Project\Resources\Task\TaskResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectListResource extends JsonResource
{
    public function toArray($request): array
    {
        if ($this->relationLoaded('tasks')) {
            $completedTasks = $this->tasks->where('status', TaskStatus::COMPLETED)->count();
            $totalTasks = $this->tasks->count();
            $assignedEducatorIds = $this->tasks
                ->flatMap->assignments
                ->where('status', AssignmentStatus::ACCEPTED)
                ->pluck('educator_id')
                ->unique();
        } else {
            $totalTasks = $this->tasks_count ?? 0;
            $completedTasks = $this->completed_tasks_count ?? 0;
            $assignedEducatorIds = collect();
        }

        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        return [
            'id' => $this->uuid,
            'title' => $this->title,
            'status' => $this['status'],
            'budget' => $this->budget ? (float) $this->budget : null,
            'isPublished' => $this->is_published,
            'creationType' => $this->creation_type,
            'requesterName' => $this->requester_name ?? $this->createdBy?->name,
            'createdAt' => $this->created_at->toISOString(),
            'deadline' => $this->deadline?->isValid() && $this->deadline->year > 0 ? $this->deadline->toDateString() : null,
            'tasksCount' => $totalTasks,
            'completedTasksCount' => $completedTasks,
            'progress' => $progress,
            'assignedEducatorsCount' => $assignedEducatorIds->isNotEmpty() ? $assignedEducatorIds->count() : 0,

            'createdBy' => $this->createdBy ? [
                'id' => $this->createdBy->uuid,
                'name' => $this->createdBy->name,
            ] : null,

            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),

            'teamAvatars' => $this->whenLoaded('teamEducators', function () {
                return $this->teamEducators->take(3)->map(function ($educator) {
                    return [
                        'id' => $educator->uuid,
                        'name' => $educator->name,
                        'avatarUrl' => $educator->profileImage,
                    ];
                });
            }),
        ];
    }
}
