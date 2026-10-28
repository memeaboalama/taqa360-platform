<?php

namespace App\Application\Api\Http\Resources\Workflow;

use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    public function toArray($this): array
    {
        return [
            'id' => $this->id,
            'workflowId' => $this->workflow_id,
            'assignedBy' => $this->assigned_by,
            'assignedTo' => $this->assigned_to,
            'status' => $this->status,
            'educatorId' => $this->educator_id,
            'educatorName' => $this->educator?->name,
            'assignedType' => $this->assigned_type,
            'bidId' => $this->bid_id ?? null,
            'budget' => $this->budget,
            'agreementNotes' => $this->instructions ?? null,
            'educatorAvatarUrl' => $this->educator?->avatar_url,
            'assignedAt' => $this->assigned_at,
            'acceptedAt' => $this->accepted_at ?? null,
        ];
    }
}
