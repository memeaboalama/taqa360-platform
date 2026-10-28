<?php

namespace App\Domain\Educator\Resource\Bids;

use Domain\Project\Resources\Project\ProjectResource;
use Domain\Project\Resources\Task\TaskFileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BidDetailResource extends JsonResource
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
            'proposal' => $this->proposal,
            'deliveryDays' => $this->delivery_days,
            'totalAmount' => $this->totalAmount,
            'status' => $this?->status,
            'createdAt' => $this->created_at->toISOString(),
            'updatedAt' => $this->updated_at->toISOString(),
            'bidTasks' => $this->whenLoaded('bidTasks', fn() => BidTaskResource::collection($this->bidTasks)),
            'attachments' => $this->whenLoaded('attachments', fn() => TaskFileResource::collection($this->attachments)),
            'links' => $this->when($this->resource->relationLoaded('links'), function () {
                $links = $this->resource->getRelation('links');
                return $links instanceof \Illuminate\Support\Collection
                    ? $links->map(fn($link) => [
                        'id' => $link->uuid,
                        'title' => $link->title,
                        'url' => $link->url,
                        'collectionName' => $link->collection_name,
                        'type' => $link->type,
                    ])->values()->toArray()
                    : [];
            }, []),

            'project' => new ProjectResource($this->whenLoaded('project')),


            // 'educator' => new EducatorResource($this->whenLoaded('educator')),
            // 'attachments' => BidAttachmentResource::collection($this->whenLoaded('attachments')),
            // 'links' => BidLinkResource::collection($this->whenLoaded('links')),

            // // Computed attributes
            // 'totalAmount' => $this->when(isset($this->total_amount), (float) $this->total_amount),
            // 'approvedTasksCount' => $this->when(isset($this->approved_tasks_count), $this->approved_tasks_count),
            // 'assignedTasksCount' => $this->when(isset($this->assigned_tasks_count), $this->assigned_tasks_count),
            // 'rejectedTasksCount' => $this->when(isset($this->rejected_tasks_count), $this->rejected_tasks_count),
            // 'status' => $this->when(isset($this->status), $this->status),
            // 'expectedDeliveryDate' => $this->when(isset($this->expected_delivery_date), $this->expected_delivery_date->format('Y-m-d')),
            // 'bidTasksCount' => $this->whenLoaded('bidTasks', fn() => $this->bidTasks->count()),
            // 'attachmentsCount' => $this->whenLoaded('attachments', fn() => $this->attachments->count()),
            // 'linksCount' => $this->whenLoaded('links', fn() => $this->links->count()),
        ];
    }
}
