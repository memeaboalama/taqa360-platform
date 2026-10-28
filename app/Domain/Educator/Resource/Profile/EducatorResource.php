<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EducatorResource extends JsonResource
{
    public function toArray($request)
    {
        $educator = $this->resource;

        return [
            'id' => $educator->id,
            'name' => $educator->name,
            'email' => $educator->email,
            'phone' => $educator->phone,
            'discipline' => $educator->discipline ?? $educator->profile?->specialization,
            'schoolName' => $educator->school_name,
            'country' => $educator->country_name ?? ($educator->profile->country->name ?? null),
            'city' => $educator->profile->city?->name ?? null,
            'status' => $educator->status,
            'rejection_reason' => $educator->rejection_reason ?? null,
            'avatarUrl' => $educator->profile_image_url ?? $educator->getProfileImageUrlAttribute(),
            'rating' => $educator->rating,
            'availabilityStatus' => $educator->availability_status,
            'bio' => $educator->bios()->latest()->first()->description ?? $educator->bio ?? null,
            'profile' => [
                'identityStatus' => $educator->profile->identity_status ?? null,
                'gender' => $educator->profile->gender ?? null,
                'birthDate' => optional($educator->profile->birth_date)?->format('Y-m-d'),
                'nationality' => $educator->profile->nationality?->name ?? null,
                'educationLevel' => $educator->profile->education_level ?? null,
                'experienceYears' => $educator->profile->experience_years ?? null,
                'employmentStatus' => $educator->profile->employment_status ?? null,
                'profileCompletion' => $educator->profile->is_complete ? 100 : ($educator->profile->completion_step ?? 0),
                'experienceLevel' => $educator->profile->experience_level ?? null,
                'idType' => $educator->profile->identity_type ?? null,
                'idNumber' => $educator->profile->identity_id ?? null,
                'idDocumentUrl' => $educator->getIdentityFrontImageUrlAttribute() ?? $educator->getPassportImageAttribute()?->url ?? null,
            ],
            'academicMajors' => $educator->academicMajors->map(function($m){
                return [
                    'id' => $m->id,
                    'majorName' => $m->approvedMajor?->name ?? $m->custom_name,
                    'isCustom' => (bool)$m->is_custom,
                    'institution' => $m->graduation_institution,
                    'graduationYear' => $m->graduation_year,
                    'degree' => $m->degree,
                    'documentUrl' => $m->certificateFile?->url ?? null,
                    'verificationStatus' => $m->approval_document ? 'VERIFIED' : 'PENDING',
                    'rejectionReason' => null,
                ];
            })->values(),
            'languages' => $educator->languages->map(function($l){
                return [
                    'id' => $l->id,
                    'name' => $l->language?->name ?? null,
                    'level' => $l->level,
                ];
            })->values(),
            'services' => $educator->services->map(function($s){
                return [
                    'id' => $s->id,
                    'name' => $s->approvedService?->name ?? $s->custom_name,
                    'description' => $s->custom_description,
                    'isCustom' => (bool)$s->is_custom,
                ];
            })->values(),
            'skills' => $educator->skills->map(function($sk){
                return [
                    'id' => $sk->id,
                    'name' => $sk->approvedSkill?->name ?? $sk->custom_name,
                    'level' => $sk->level,
                    'isCustom' => (bool)$sk->is_custom,
                ];
            })->values(),
            'workExperience' => [],
            'certifications' => [],
            'socialLinks' => $educator->socialLinks->map(function($sl){
                return [
                    'platform' => $sl->platform,
                    'url' => $sl->url,
                ];
            })->values(),
            'paymentInfo' => [
                'bankName' => $educator->bankInfo?->name ?? null,
                'accountHolderName' => $educator->bankInfo?->account_holder_name ?? null,
                'iban' => $educator->bankInfo?->iban ?? null,
            ],
        ];
    }
}
