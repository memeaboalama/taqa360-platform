<?php

namespace App\Domain\Educator\Resource\Task;

use App\Application\Project\Enums\AssignmentStatus;
use App\Domain\Educator\Resource\AssignmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request): array
    {
        $subTasks = $this->subTasks;
        $completedSubTasks = $subTasks->where('completed', true)->count();
        $totalSubTasks = $subTasks->count();
        $statusValue = $this->status instanceof \UnitEnum ? $this->status->value : $this->status;
        $progress = $totalSubTasks > 0
            ? (int) round(($completedSubTasks / $totalSubTasks) * 100)
            : (strtoupper((string) $statusValue) === 'COMPLETED' ? 100 : 0);
        $deadline = null;
        if ($this->deadline) {
            $deadline = $this->deadline instanceof \DateTimeInterface
                ? $this->deadline->format('Y-m-d')
                : substr((string) $this->deadline, 0, 10);
        }

        return [
            'id' => (string) $this->uuid,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $statusValue,
            'budget' => $this->budget !== null ? (float) $this->budget : null,
            'deadline' => $deadline,
            'createdAt' => $this->created_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->toIso8601String(),
            'progress' => $progress,
            'subTasksCount' => $totalSubTasks,
            'completedSubTasksCount' => $completedSubTasks,

            'assignedEducator' => $this->whenLoaded('assignments', function () {
                $educator = $this->assignments?->where('assigned_to', auth()->id())->first();
                if (!$educator) {
                    return null;
                }

                $assignedType = $educator->assigned_type instanceof \UnitEnum ? $educator->assigned_type->value : $educator->assigned_type;
                $status = $educator->status instanceof \UnitEnum ? $educator->status->value : $educator->status;
                $assignedAt = null;
                if ($educator->assigned_at) {
                    $assignedAt = $educator->assigned_at instanceof \DateTimeInterface
                        ? $educator->assigned_at->format('Y-m-d')
                        : substr((string) $educator->assigned_at, 0, 10);
                }

                return [
                    'id' => (string) $educator->uuid,
                    'assigned_type' => $assignedType,
                    'status' => $status,
                    'amount' => $educator->amount !== null ? (float) $educator->amount : null,
                    'assigned_at' => $assignedAt,
                ];
            }),

            'project' => $this->whenLoaded('project', function () {
                return $this->project ? [
                    'id' => (string) $this->project->uuid,
                    'title' => $this->project->title,
                ] : null;
            }),
        ];
    }
}
