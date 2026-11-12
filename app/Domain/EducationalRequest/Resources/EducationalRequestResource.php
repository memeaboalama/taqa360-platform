<?php

namespace App\Domain\EducationalRequest\Resources;

use App\Support\Resources\Edu\Attachment\AttachmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationalRequestResource extends JsonResource
{
    public function toArray($request)
    {
        $serviceType = $this->type instanceof \BackedEnum ? $this->type->value : $this->type;
        $brandFields = config('app.brand') === 'huloolcom'
            ? [
                'subject' => $this->academicMajor?->name ?? '',
                'academicMajor' => $this->academicMajor?->name,
                'educationLevel' => $this->education_level,
            ]
            : [
                'projectSiteType' => $this->project_site_type,
                'solarSystemType' => $this->solar_system_type,
            ];

        $resource = [
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
            'serviceType' => $serviceType,
            'serviceId' => $this->whenLoaded('service', fn () => $this->service?->uuid),
            'serviceName' => $this->whenLoaded('service', fn () => $this->service?->name),
            'paymentStatus' => $this->payment_status,
            'executionStatus' => $this->execution_status,
            'budget' => $this->project?->budget !== null
                ? (float) $this->project->budget
                : ($this->budget !== null ? (float) $this->budget : null),
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
            'activityLog' => $this->whenLoaded('activityLog', fn () => $this->activityLog
                ->map(fn ($activity) => [
                    'id' => (string) $activity->id,
                    'type' => $activity->activity_type,
                    'fromStatus' => $activity->from_status,
                    'toStatus' => $activity->to_status,
                    'reason' => $activity->reason,
                    'details' => $activity->details ? json_decode($activity->details, true) : null,
                    'actor' => $activity->actor_name ?? 'الإدارة',
                    'timestamp' => $activity->created_at?->toIso8601String(),
                ])
                ->values()
            ),
            'project' => $this->whenLoaded('project', function () {
                return [
                    'id' => $this->project->uuid,
                    'title' => $this->project->title,
                    'status' => $this->project->status,
                ];
            }) ?? null,
            'createdAt' => $this->created_at,
        ];

        return array_merge($resource, $brandFields);
    }
}
