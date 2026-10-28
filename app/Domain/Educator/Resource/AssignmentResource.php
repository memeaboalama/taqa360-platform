<?php

namespace App\Domain\Educator\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $status = $this->status instanceof \UnitEnum ? $this->status->value : $this->status;
        $assignedType = $this->assigned_type instanceof \UnitEnum ? $this->assigned_type->value : $this->assigned_type;
        $taskStatus = null;
        if ($this->relationLoaded('task') && $this->task) {
            $taskStatus = $this->task->status instanceof \UnitEnum ? $this->task->status->value : $this->task->status;
        }
        return [
            'id' => (string) $this->uuid,
            'assigned_type' => $assignedType,
            'status' => $status,
            'amount' => $this->amount !== null ? (float) $this->amount : null,
              'agreementNotes' => $this->agreement_notes,
              'rejectionReason' => $this->rejection_reason,
            'assigned_at' => $this->assigned_at?->toDateString(),
            'accepted_at' => $this->accepted_at?->toDateString(),
            'projectTitle' => $this->task?->project?->title ?? null,

            'task' => $this->whenLoaded('task', function () use ($taskStatus) {
                return [
                    'id' => (string) $this->task->uuid,
                    'title' => $this->task?->title,
                    'status' => $taskStatus,
                    'tags' => $this->task?->tags,
                    'description' => $this->task?->description,
                    'deadline' => $this->task?->deadline && $this->task->deadline->isValid() && $this->task->deadline->year > 0 ? $this->task->deadline->toDateString() : null,
                ];
            }),
        ];
    }
}
