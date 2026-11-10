<?php

namespace App\Domain\EducationalRequest\Resources;

use App\Support\Resources\Edu\Attachment\AttachmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationalListRequestResource extends JsonResource
{
    public function toArray($request)
    {
        $serviceType = $this->type instanceof \BackedEnum ? $this->type->value : $this->type;

        return [
            'id' => $this->uuid,
            'code' => '#'. $this->id,
            'title' => $this->title,
            'requesterName' => $this->requester->name,
            'serviceType' => $serviceType,
            'projectSiteType' => $this->project_site_type,
            'solarSystemType' => $this->solar_system_type,
            'status' => $this->status,
            'educationLevel' => $this->education_level,
            'paymentStatus' => $this->payment_status,
            'executionStatus' => $this->execution_status,
            'budget' => $this->project?->budget !== null
                ? (float) $this->project->budget
                : ($this->budget !== null ? (float) $this->budget : null),
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
