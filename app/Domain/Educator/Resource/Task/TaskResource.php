<?php

namespace App\Domain\Educator\Resource\Task;

use App\Application\Project\Enums\AssignmentStatus;
use App\Domain\Educator\Resource\AssignmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'description' => $this->description ,
            'status' => $this->status,
            'budget' => $this->budget ? (float) $this->budget : null,
            'deadline' => $this->deadline?->isValid() && $this->deadline->year > 0 ? $this->deadline->toDateString() : null,
            'progress' => $progress,
            'subTasksCount' => $totalSubTasks,
            'completedSubTasksCount' => $completedSubTasks,

            'assignedEducator' => $this->whenLoaded('assignments', function () {
                $educator = $this->assignments->where('assigned_to', auth()->id())->first();
                return [
                    'id' => $educator->uuid,
                    'assigned_type' => $educator->assigned_type,
                    'status' => $educator->status,
                    'amount' => $educator->amount ? (float) $educator->amount : null,
                    'assigned_at' => $educator->assigned_at?->toDate()

                ];
            }),

            'project' => $this->whenLoaded('project', function () {
                return [
                    'id' => $this->project->uuid,
                    'title' => $this->project->title,
                ];
            }),
        ];
    }
}
