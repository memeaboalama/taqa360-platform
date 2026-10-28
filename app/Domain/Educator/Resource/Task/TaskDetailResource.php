<?php

namespace App\Domain\Educator\Resource\Task;

use App\Application\Project\Enums\AssignmentStatus;
use App\Domain\Educator\Resource\AssignmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        $completedSubTasks = $this->subTasks->where('completed', true)->count();
        $totalSubTasks = $this->subTasks->count();
        $progress = $totalSubTasks > 0 ? round(($completedSubTasks / $totalSubTasks) * 100) :
                ($this->status === 'COMPLETED' ? 100 : 0);

        return [
            'id' => $this->uuid,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'budget' => $this->budget ? (float) $this->budget : null,
            'deadline' => $this->deadline?->isValid() && $this->deadline->year > 0 ? $this->deadline->toDateString() : null,
            'progress' => $progress,
            'subTasks' => $this->whenLoaded('subTasks', function () {
                return SubTaskResource::collection($this->subTasks->load('attachments'));
            }),

            'assignedEducator' => $this->whenLoaded('assignments', function () {
                $educator = $this->assignments->where('assigned_to', auth()->id())->first();
                if (!$educator) return null;
                return [
                    'id' => $educator->uuid,
                    'assigned_type' => $educator->assigned_type,
                    'status' => $educator->status,
                    'amount' => $educator->amount ? (float) $educator->amount : null,
                    'assigned_at' => $educator->assigned_at?->toDate()
                ];
            }),

            'subTasksCount' => $totalSubTasks,
            'completedSubTasksCount' => $completedSubTasks,
            'attachmentsCount' => $this?->attachments->count(),
            'attachments' => $this->whenLoaded('attachments', function () {
                return TaskFileResource::collection($this->attachments);
            }),

            'createdAt' => $this->created_at?->format('Y-m-d H:i:s'),
            'creationDate' => $this->creation_date ?? $this->created_at?->format('Y-m-d H:i:s'),

            'notes' => $this->whenLoaded('notes', function () {
                return \Domain\Project\Resources\Task\TaskNoteResource::collection($this->notes);
            }, []),
            'activityLog' => $this->whenLoaded('activityLog', function () {
                return $this->activityLog
                    ->sortByDesc('timestamp')
                    ->map(fn($log) => [
                        'id' => (string) $log->uuid,
                        'messageKey' => $log->message_key,
                        'messagePayload' => $log->message_payload ?? [],
                        'actorName' => $log->actor_name,
                        'timestamp' => $log->timestamp?->format('Y-m-d H:i:s'),
                    ])
                    ->values();
            }, []),

            'project' => $this->whenLoaded('project', function () {
                return [
                    'id' => $this->project->uuid,
                    'title' => $this->project->title,
                ];
            }),
        ];
    }
}
