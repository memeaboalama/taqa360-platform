<?php

namespace Domain\Project\Resources\Task;

use App\Application\Project\Enums\AssignmentStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        $completedSubTasks = $this->subTasks->where('completed', true)->count();
        $totalSubTasks = $this->subTasks->count();
        $progress = $totalSubTasks > 0 ? round(($completedSubTasks / $totalSubTasks) * 100) :
                ($this->status === 'COMPLETED' ? 100 : 0);

        $assignedCount = $this->assignments->where('status', AssignmentStatus::ACCEPTED)->count();
        $invitedCount = $this->assignments->where('status', AssignmentStatus::PENDING)->count();
        $bidCount = $this?->project->bids->count();

        return [
            'id' => $this->uuid,
            'title' => $this->title,
            // 'description' => substr($this->description, 0, 150) . (strlen($this->description) > 150 ? '...' : ''),
            'description' => $this->description,
            'status' => $this->status,
            'budget' => $this->budget ? (float) $this->budget : null,
            'deadline' => $this->deadline?->isValid() ? $this->deadline->toDateString() : null,
            'isPublic' => $this->is_public,
            'biddingStatus' => $this->bidding_status,
            'creationBy' => $this->whenLoaded('createdBy', function () {
                return $this->createdBy ? [
                    'id' => $this->createdBy->uuid,
                    'name' => $this->createdBy->name,
                ] : null;
            }),

            'creationDate' => $this->created_at->toDateString(),

            'progress' => $progress,
            'subTasks' => $this->whenLoaded('subTasks', function () {
                return SubTaskResource::collection($this->subTasks->load('attachments'));
            }),

            'bids' => [],
            'subTasksCount' => $totalSubTasks,
            'completedSubTasksCount' => $completedSubTasks,
            'assignedEducatorsCount' => $assignedCount,
            'invitedEducatorsCount' => $invitedCount,
            'bidsCount' => $bidCount,
            'attachments' => $this->whenLoaded('attachments', function () {
                return TaskFileResource::collection($this->attachments);
            }),
            'attachmentsCount' => $this->attachments->count(),
            'deliverables' => TaskFileResource::collection($this->attachments->whereIn('collection_name', ['deliverables', 'task_deliverables'])->values()),
            'notes' => $this->whenLoaded('notes', function () {
                return TaskNoteResource::collection($this->notes);
            }),
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
            }),
            'project' => $this->whenLoaded('project', function () {
                return [
                    'id' => $this->project->uuid,
                    'title' => $this->project->title,
                ];
            }),
            'assignedEducators' => AssignmentResource::collection($this->assignments->load('educator')),
        ];
    }
}
