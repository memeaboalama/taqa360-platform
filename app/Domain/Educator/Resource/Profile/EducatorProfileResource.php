<?php

namespace App\Domain\Educator\Resource\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class EducatorProfileResource extends JsonResource
{
    public function toArray($request)
    {
        $educator = $this->resource;

        return [
            'id' => $educator->uuid,
            'name' => $educator->name,
            'email' => $educator->email,
            'phone' => $educator->phone,
            'discipline' => $educator->discipline ?? $educator->profile?->specialization,
            'specialization' => $educator->profile?->specialization,
            'schoolName' => $educator->school_name,
            'status' => $educator->status,
            'rejection_reason' => $educator->rejection_reason ?? null,
            'avatarUrl' => $educator->profile_image_url ?? $educator->getProfileImageUrlAttribute(),
            'rating' => $educator->rating,
            'availabilityStatus' =>  $educator->availability_status ?? null,
            'bio' => [
                'title' => $educator->bios->title ?? null,
                'description' => $educator->bios->description ?? null,
            ],

            'basicInfo' => [
                'identityStatus' => $educator->profile->identity_status ?? null,
                'gender' => $educator->profile->gender ?? null,
                'birthDate' => optional($educator->profile?->birth_date)?->format('Y-m-d'),
                'nationality' => $educator->profile->nationality?->title ?? null,
                'country' => $educator->profile->country?->title ?? null,
                'city' => $educator->profile->city?->title ?? null,
                'educationLevel' => $educator->profile->education_level ?? null,
                'experienceYears' => $educator->profile->experience_years ?? null,
                'employmentStatus' => $educator->profile->employment_status ?? null,
                'profileCompletion' => $educator->profile?->is_complete ? 100 : ($educator->profile->completion_step ?? 0),
                'experienceLevel' => $educator->profile->experience_level ?? null,
                'idType' => $educator->profile->identity_type ?? null,
                'idNumber' => $educator->profile->identity_id ?? null,
                'idDocumentUrl' => $educator->getIdentityFrontImageUrlAttribute() ?? null,
                'idDocumentBackUrl' => $educator->getIdentityBackImageUrlAttribute() ?? null,
                'verificationStatus' => $educator->profile->identity_status ?? null,
                'rejectionReason' => $educator->profile->rejection_reason ?? null,
                'suspensionReason' => $educator->profile->rejection_reason ?? null,
                'isComplete' => $educator->profile?->is_complete ?? false,
            ],

            'academicMajors' => AcademicMajorResource::collection($educator->academicMajors),
            'languages' => LanguageResource::collection($educator->languages->load('language')),

            'services' => EducatorServiceResource::collection($educator->services),
            'skills' => EducatorSkillResource::collection($educator->skills->load('approvedSkill')),

            'achievements' => AchievementResource::collection($educator->achievements->load('file')),
            'socialLinks' => $educator->socialLinks->map(function($link){
                return [
                    'id' => $link->uuid,
                    'platform' => $link->platform,
                    'url' => $link->url,
                ];
            })->values(),

            'bankInfo' => $educator->bankInfo ? new BankInfoResource($educator->bankInfo) : [],
        ];
    }
}
