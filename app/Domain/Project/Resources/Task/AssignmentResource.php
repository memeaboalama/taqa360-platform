<?php

namespace Domain\Project\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->uuid,
            'assigned_type' => $this->assigned_type,
            'status' => $this->status,
            'amount' => $this->amount ? (float) $this->amount : null,
            'agreementNotes' => $this->agreement_notes,
            'rejectionReason' => $this->rejection_reason,
            'assigned_at' => $this->assigned_at?->toDateString(),
            'accepted_at' => $this->accepted_at?->toDateString(),
            'educator' => [
                'id' => (string) $this->educator->uuid,
                'name' => $this->educator?->name,
                'avatarUrl' => $this->educator?->avatar_url,
            ],
        ];
    }
}
