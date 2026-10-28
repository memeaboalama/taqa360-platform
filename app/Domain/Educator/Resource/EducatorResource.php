<?php

namespace App\Domain\Educator\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EducatorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if (!$this->resource) {
            return [];
        }
        $profile = $this->profile;
        return [
            'id' => (string) $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'discipline' => $this->discipline ?? $profile?->specialization,
            'schoolName' => $this->school_name,
            'country' => $profile?->country?->title,
            'city' => $profile?->city?->title,
            'status' => $this->status,
            'avatarUrl' => $this->profileImage?->url,
            'rating' => (float) $this?->rating ?? 0.0,
            'availabilityStatus' => "متاح",
            'email_verified_at' => is_null($this->email_verified_at) ? false : true,
            'is_accepted' => $this->is_accepted,
            'account_status' => $this->status,
            'role' => "educator",
            'experienceLevel' => $profile?->experience_level,
            'basicInfo' => $profile ? [
                'experienceLevel' => $profile->experience_level,
                'gender' => $profile->gender,
                'identityStatus' => $profile->identity_status,
                'birthDate' => $profile->birth_date?->format('Y-m-d'),
                'nationality' => $profile->nationality?->title,
                'country' => $profile->country?->title,
                'city' => $profile->city?->title,
                'educationLevel' => $profile->education_level,
                'experienceYears' => $profile->experience_years,
                'employmentStatus' => $profile->employment_status,
                'profileCompletion' => $profile->is_complete ? 100 : ($profile->completion_step ?? 0),
            ] : null,
            'academicMajors' => \App\Domain\Educator\Resource\Profile\AcademicMajorResource::collection($this->whenLoaded('academicMajors')),
            'createdAt' => $this->created_at,
            'skills' => $this->whenLoaded('skills', fn() => $this->skills->map(fn($skill) => [
                'id' => (string) $skill->uuid,
                'name' => $skill->title,
            ])->values()->toArray()),
        ];
    }
}
