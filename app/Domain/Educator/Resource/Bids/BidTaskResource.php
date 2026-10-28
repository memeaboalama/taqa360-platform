<?php

namespace App\Domain\Educator\Resource\Bids;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BidTaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'taskId' => $this->task->uuid,
            'amount' => (float) $this->amount,
            'status' => $this->status,
            'task' => [
                'id' => $this->task->uuid,
                'title' => $this->task->title,
                'description' => $this->task->description,
                'budget' => $this->task->budget ? (float) $this->task->budget : null,
                'status' => $this->task->status,
            ]
        ];
    }
}
