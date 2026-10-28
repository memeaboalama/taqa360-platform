<?php

namespace App\Domain\Educator\Resource\Bids;

use App\Domain\Educator\Resource\EducatorResource;
use Domain\Project\Resources\Project\ProjectResource;
use Domain\Project\Resources\Task\TaskFileResource;
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
            'projectTitle' => $this->project->title,
            'proposal' => $this->proposal,
            'deliveryDays' => $this->delivery_days,
            'bidTasks' => $this?->bidTasks,
            'totalAmount' => $this->totalAmount,
            'status' => $this?->status,
            'attachments' => TaskFileResource::collection($this->whenLoaded('attachments')),
            'createdAt' => $this->created_at->toISOString(),
            'links' => $this?->links,


            // 'project' => new ProjectResource($this->project),
            // 'educator' => new EducatorResource($this->educator)
        ];
    }
}
