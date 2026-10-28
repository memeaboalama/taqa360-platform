<?php

namespace App\Domain\EducationalRequest\Resources;

use App\Support\Resources\Edu\Attachment\AttachmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationalRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->uuid,
            'code' => '#'. $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'requester' => $this->whenLoaded('requester', function () {
                return  [
                    'id' => $this->requester?->uuid,
                    'name' => $this->requester?->name,
                    'email' => $this->requester?->email,
                    'phone' => $this->requester?->phone,
                    'gender' => $this->requester?->gender,
                    'city' => $this->requester?->city,
                    'address' => $this->requester?->address ?: ($this->requester?->country_name ?: ''),
                ];
            }),

            'status' => $this->status,
            'reviewedAt' => $this->reviewed_at?->toIso8601String(),
            'subject' => $this->academicMajor?->name ?? '',
            'academicMajor' => $this->academicMajor?->name,
            'educationLevel' => $this->education_level,
            'paymentStatus' => $this->payment_status,
            'executionStatus' => $this->execution_status,
            'budget' =>  $this->budget !== null ? (float) $this->budget : ($this->project ? (float) $this->project->budget : null),
            'urgencyLevel' => $this->urgency_level,
            'pricingType' => (is_object($this->type) ? $this->type->value : (string) $this->type) === 'hourly' ? 'HOURLY' : 'FIXED',
            'expectedDeliveryDate' => $this->project?->deadline?->toDateString(),
            'dedline' => $this->project?->deadline?->toDateString(),
            // 'attachments' => AttachmentResource::collection($this->whenLoaded('attachments')),
            'attachments' => $this->attachments->map(function($attachment){
                return [
                    'id' => $attachment->uuid,
                    'name' => $attachment->title,
                    'original_name' => $attachment->original_name,
                    'type' => $attachment->type,
                    'collection_name' => $attachment->collection_name,
                    'size' => $attachment->size,
                    'url' => $attachment->url,
                ];
            }),

            'finalAgreement' => $this->final_agreement,
            'additionalNotes' => $this->additional_notes,
            'activityLog' => [],
            'project' => $this->whenLoaded('project', function () {
                return [
                    'id' => $this->project->uuid,
                    'title' => $this->project->title,
                    'status' => $this->project->status,
                ];
            }) ?? null,
            'createdAt' => $this->created_at,
        ];



    }
}
