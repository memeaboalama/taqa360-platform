<?php

namespace Domain\Project\Resources\Project;

use App\Application\Project\Enums\AssignmentStatus;
use App\Application\Project\Enums\TaskStatus;
use App\Domain\EducationalRequest\Resources\EducationalListRequestResource;
use App\Domain\EducationalRequest\Resources\EducationalRequestResource;
use App\Domain\Educator\Resource\Bids\BidResource;
use App\Domain\Project\Resources\Bids\ProjectBidTaskResource;
use Domain\Project\Resources\Task\TaskResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $completedTasks = $this->tasks->where('status', TaskStatus::COMPLETED)->count();
        $totalTasks = $this->tasks->count();
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        $totalBudget = is_null($this->budget) ? null : (float) $this->budget;
        $assignedCost = $this->tasks
            ->flatMap->assignments
            ->where('status', AssignmentStatus::ACCEPTED)
            ->sum('amount') ?? 0;

        return [
            'id' => $this->uuid,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'tags' => $this->tags ?? [],
            'budget' => $totalBudget,
            'isPublished' => $this->is_published,
            'creationType' => $this->creation_type,
            'requesterName' => $this->requester_name ?? $this->createdBy?->name,
            'createdAt' => $this->created_at?->toISOString(),
            'deadline' => $this->deadline?->isValid() && $this->deadline->year > 0 ? $this->deadline->toDateString() : null,
            'educationalRequestId' => $this->educational_request_id ? (string) $this->educational_request_id : null,

            'createdBy' => $this->whenLoaded('createdBy', fn() => $this->createdBy ? [
                'id' => $this->createdBy->uuid,
                'name' => $this->createdBy->name,
            ] : null),

            'progress' => [
                'percentage' => $progress,
                'completedTasks' => $completedTasks,
                'totalTasks' => $totalTasks,
                'remainingTasks' => $totalTasks - $completedTasks,
            ],

            'financial' => [
                'totalBudget' => $totalBudget,
                'assignedCost' => (float) $assignedCost,
                'remainingBudget' => $totalBudget - $assignedCost,
                'utilizationPercentage' => $totalBudget > 0 ? round(($assignedCost / $totalBudget) * 100) : 0,
            ],

            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            // 'bids' => BidResource::collection($this->whenLoaded('bids')),


            'bidsTasks' => $this->whenLoaded('bids', fn() => $this->bids
                ->flatMap(fn($bid) => $bid->bidTasks)
                ->map(fn($bidTask) => new ProjectBidTaskResource($bidTask))
            ),

            'educationalRequest' => $this->whenLoaded('educationalRequest', fn() => new EducationalListRequestResource($this->educationalRequest)),

            // Was a hardcoded empty array, which is why the "سجل الأحداث" tab was
            // always empty no matter what happened on the project.
            'activityLog' => $this->whenLoaded('activityLog', fn() => $this->activityLog
                ->sortByDesc('timestamp')
                ->map(fn($log) => [
                    'id' => (string) $log->uuid,
                    'messageKey' => $log->message_key,
                    'messagePayload' => $log->message_payload ?? [],
                    'actorName' => $log->actor,
                    'timestamp' => $log->timestamp?->format('Y-m-d H:i:s'),
                ])
                ->values()
            ),

            'team' => $this->whenLoaded('teamEducators', fn() => $this->teamEducators->map(fn($educator) => [
                'id' => $educator->uuid,
                'name' => $educator->name,
                'avatarUrl' => $educator->profileImage?->url,
            ])),
            // 'team' => $this->whenLoaded('teamEducators', function () {
            //     return EducatorListResource::collection($this->teamEducators);
            // }),
        ];
    }
}
