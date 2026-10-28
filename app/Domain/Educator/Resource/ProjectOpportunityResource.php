<?php

namespace App\Domain\Educator\Resource;

use App\Domain\Project\Resources\Bids\BidTaskResource;
use Domain\Project\Resources\Task\TaskFileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProjectOpportunityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $educator = Auth::user();
        // One query serves both flags below instead of a separate exists() call,
        // and guarantees `bid` is never null while hasEducatorBidForOpportunity is true.
        $educatorBid = $educator
            ? $this->bids->where('educator_id', $educator->id)->first()
            : null;

        return [
            'id' => $this->uuid,
            'title' => $this->title,
            'description' => $this->description,
            'budget' => $this->budget ?? null,
            'tags' => $this->tags ?? [],
            'createdAt' => $this->created_at->toISOString(),
            'deadline' => $this->deadline?->isValid() && $this->deadline->year > 0 ? $this->deadline->toDateString() : null,
            'hasEducatorBidForOpportunity' => (bool) $educatorBid,
            // The "your previous bid" block on the educator screen renders bidTasks,
            // attachments and links, so all three have to be exposed here.
            'bid' => $educatorBid ? [
                'id' => $educatorBid->uuid,
                'educatorId' => (string) $educatorBid->educator_id,
                'proposal' => $educatorBid->proposal,
                'deliveryDays' => $educatorBid->delivery_days,
                'totalAmount' => $educatorBid->totalAmount,
                'createdAt' => $educatorBid->created_at?->format('Y-m-d H:i:s'),
                'bidTasks' => $educatorBid->relationLoaded('bidTasks')
                    ? BidTaskResource::collection($educatorBid->bidTasks)
                    : [],
                'attachments' => $educatorBid->relationLoaded('attachments')
                    ? TaskFileResource::collection($educatorBid->attachments)
                    : [],
                // `links` is both a json column and a morphMany relation on Bid, so the
                // attribute always wins. Read the loaded relation explicitly, the same
                // way BidDetailResource does.
                'links' => $educatorBid->relationLoaded('links')
                    ? $educatorBid->getRelation('links')->map(fn($link) => [
                        'id' => $link->uuid,
                        'title' => $link->title,
                        'url' => $link->url,
                    ])->values()
                    : [],
            ] : null,

            'tasks' => $this->tasks->map(function($task) use ($educator) {
                return [
                    'id' => $task->uuid,
                    'title' => $task->title,
                    'description' => $task->description,
                    'budget'=> $task->budget ?? null,
                    'deadline' => $task->deadline?->toDateString(),
                    'isPublic' => $task->is_public,
                    'biddingStatus' => $task->bidding_status,
                    'createdAt' => $task->created_at,
                    'hasEducatorBidForTask' => $educator ? \Domain\Project\Models\Bid::hasEducatorBidForTask($educator, $task->uuid) : false,
                    'BidDetailForTask' => $task->getEducatorBidTask($educator) ? [
                        'id' => $task->getEducatorBidTask($educator)->uuid,
                        'status' => $task->getEducatorBidTask($educator)->status->value,
                        'status_label' => $task->getEducatorBidTask($educator)->status->label(),
                        'amount' => (float) $task->getEducatorBidTask($educator)->amount,
                        'rejection_reason' => $task->getEducatorBidTask($educator)->rejection_reason,
                        'approval_message' => $task->getEducatorBidTask($educator)->approval_message,
                        'created_at' => $task->getEducatorBidTask($educator)->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $task->getEducatorBidTask($educator)->updated_at->format('Y-m-d H:i:s'),
                    ] : null,
                    'subTasks' => $task->subTasks->map(function ($subTask) {
                        return [
                            'id' => $subTask->uuid,
                            'title' => $subTask->title,
                            'attachments' => $subTask->attachments->where('uploader_type', 'Domain\Admin\Models\Admin')->map(function ($attachment) {
                                return [
                                    'id' => $attachment->uuid,
                                    'name' => $attachment->title,
                                    'size' => $attachment->size,
                                    'url' => $attachment->url,
                                    'uploadedAt' => $attachment->uploaded_at?->format('Y-m-d H:i:s') ?? null,
                                ];
                            }),
                        ];
                    }),

                    'attachments' => $task->attachments->map(function ($attachment) {
                        return [
                            'id' => $attachment->uuid,
                            'title' => $attachment->title,
                            'original_name' => $attachment->original_name,
                            'size' => $attachment->size,
                            'url' => $attachment->url,
                        ];
                    }),
                ];
            }),
        ];
    }
}
