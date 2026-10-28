<?php

namespace App\Domain\Project\Resources\Bids;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectBidTaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'bidId' => $this->bid->uuid,
            'taskId' => $this->task->uuid,
            'taskTitle' => $this->task->title,
            'taskStatus' => $this->task->status,
            'taskBudget' => $this->task->budget ? (float) $this->task->budget : null,

            'status' => $this->status,
            'proposal' => $this->bid?->proposal ?? null,
            'deliveryDays' => $this->bid?->delivery_days,
            'amount' => $this->amount,

            'bidTotalAmount' => $this->bid?->total_amount,
            'aducator' => [
                'id' => $this->bid->educator->uuid,
                'name' => $this->bid->educator->name,
                'rating' => $this->bid?->educator?->rating ?? 2.0,
            ],
            'createdAt' => $this->created_at->toISOString(),

            'rejection_reason' => $this->rejection_reason,
            'approval_message' => $this->approval_message,
        ];
    }
}
