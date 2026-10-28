<?php

namespace Domain\Project\Resources\Task;

// use App\Application\Api\Http\Resources\Workflow\AssignmentResource;
use App\Application\Project\Enums\AssignmentStatus;
use App\Domain\Project\Resources\Bids\ProjectBidTaskResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            // 'description' => $this->description ? (substr($this->description, 0, 150) . (strlen($this->description) > 150 ? '...' : '')) : null,
            'description' => $this->description ,
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
            'subTasksCount' => $totalSubTasks,
            'completedSubTasksCount' => $completedSubTasks,
            'assignedEducatorsCount' => $assignedCount,
            'invitedEducatorsCount' => $invitedCount,
            'bidsCount' => $bidCount,
            // 'attachmentsCount' => $this->attachments->count(),
            // 'deliverablesCount' => $this->deliverables->count(),

            'project' => $this->whenLoaded('project', function () {
                return [
                    'id' => $this->project->uuid,
                    'title' => $this->project->title,
                ];
            }),

            // 'bids' => ProjectBidTaskResource::collection($this->bidTasks),
            'assignedEducators' => AssignmentResource::collection($this->assignments->load('educator')),
            // 'assignedEducators' => AssignmentResource::collection($this->whenLoaded('assignments')),
        ];
    }
}
