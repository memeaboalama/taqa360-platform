<?php

namespace App\Domain\Project\Resources\Bids;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BidResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'projectId' => $this->project->uuid,
            'educatorId' => $this->educator->uuid,
            'project_title' => $this->project->title,
            'proposal' => $this->proposal,
            'deliveryDays' => $this->delivery_days,
            'totalAmount' => $this->totalAmount,
            'status' => $this?->status,
            // Per-task status is what tells the client whether the offer can still
            // be edited (see Bid::isUpdatable()). Without it the list screen has no
            // way to hide the edit button for an agreed/assigned offer.
            'bidTasks' => $this->whenLoaded('bidTasks', fn () => BidTaskResource::collection($this->bidTasks)),
            'createdAt' => $this->created_at->toISOString(),
            'updatedAt' => $this->updated_at->toISOString(),
        ];
    }
}
