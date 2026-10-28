<?php

namespace App\Domain\EducationalRequest\Resources;

use App\Support\Resources\Edu\Attachment\AttachmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationalListRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->uuid,
            'code' => '#'. $this->id,
            'title' => $this->title,
            'requesterName' => $this->requester->name,
            'status' => $this->status,
            'educationLevel' => $this->education_level,
            'paymentStatus' => $this->payment_status,
            'executionStatus' => $this->execution_status,
            'budget' =>  (float) $this->project?->budget ?? null,
            'dedline' => $this->project?->deadline?->toDateString(),
            'project' => $this->whenLoaded('project', function () {
                return [
                    'id' => $this->project->uuid,
                    'title' => $this->project->title,
                ];
            }),
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
