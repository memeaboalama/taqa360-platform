<?php

namespace App\Domain\Project\Resources\Bids;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BidTaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $task = $this->task;
        return [
            'id' => $this->uuid,
            'taskId' => $task?->uuid,
            'amount' => (float) $this->amount,
            'status' => $this->status,
            'task' => $task ? [
                'id' => $task->uuid,
                'title' => $task->title,
                'description' => $task->description,
                'budget' => $task->budget ? (float) $task->budget : null,
                'status' => $task->status,
                'subTasks' => $task->relationLoaded('subTasks')
                    ? $task->subTasks->map(function ($subTask) {
                        return [
                            'id' => $subTask->uuid,
                            'title' => $subTask->title,
                            'attachments' => $subTask->relationLoaded('attachments')
                                ? $subTask->attachments->where('uploader_type', 'Domain\Admin\Models\Admin')->map(function ($attachment) {
                                    return [
                                        'id' => $attachment->uuid,
                                        'name' => $attachment->title,
                                        'size' => $attachment->size,
                                        'url' => $attachment->url,
                                        'uploadedAt' => $attachment->uploaded_at?->format('Y-m-d H:i:s') ?? null,
                                    ];
                                }) : [],
                        ];
                    }) : [],
            ] : null,
        ];
    }
}
