<?php

namespace App\Application\Educator\Controllers\Api;

use App\Application\Educator\Enums\EducatorStatus;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Domain\Educator\Models\Educator;

class PublicEducatorProfileController extends Controller
{
    use ApiResponseTraitV2;

    public function show(Educator $educator)
    {
        if ($educator->status !== EducatorStatus::ACTIVE) {
            abort(404);
        }

        $educator->load([
            'profile.nationality',
            'profile.country',
            'profile.city',
            'bios',
            'academicMajors.approvedMajor',
            'achievements',
            'services.approvedService',
            'skills.approvedSkill',
            'socialLinks',
        ]);

        $achievements = $educator->achievements;
        $certificationTypes = ['certificate', 'course', 'workshop'];
        $profileImage = $educator->files()->where('collection_name', 'profile_image')->first();

        return $this->responseData([
            'profile' => [
                'id' => $educator->uuid,
                'name' => $educator->name,
                'avatarUrl' => $profileImage?->url ?? $educator->profile_image_url,
                'email' => $educator->profile?->is_email_public ? $educator->email : null,
                'phone' => $educator->profile?->is_phone_public ? $educator->phone : null,
                'identityType' => $educator->profile?->is_identity_id_public ? $educator->profile?->identity_type : null,
                'identityNumber' => $educator->profile?->is_identity_id_public ? $educator->profile?->identity_id : null,
                'nationality' => $educator->profile?->is_nationality_public ? $educator->profile?->nationality?->title : null,
                'gender' => $educator->profile?->is_gender_public ? $educator->profile?->gender : null,
                'country' => $educator->profile?->is_country_public ? $educator->profile?->country?->title : null,
                'city' => $educator->profile?->is_city_public ? $educator->profile?->city?->title : null,
                'headline' => $educator->discipline ?? $educator->profile?->specialization,
                'bioTitle' => $educator->bios?->title,
                'bio' => $educator->bios?->description,
                'educationLevel' => $educator->profile?->education_level,
                'experienceLevel' => $educator->profile?->is_experience_level_public
                    ? $educator->profile?->experience_level
                    : null,
                'experienceYears' => !$educator->profile?->is_experience_level_public || $educator->profile?->experience_years === null
                    ? null
                    : (int) $educator->profile->experience_years,
                'academicMajors' => $educator->academicMajors->map(fn ($major) => [
                    'majorName' => $major->is_custom ? $major->custom_name : $major->approvedMajor?->name,
                    'degree' => $major->degree?->value,
                    'institution' => $major->graduation_institution,
                    'graduationYear' => $major->graduation_year,
                ])->values(),
                'workExperience' => $achievements->where('type', 'work_experience')->map(fn ($experience) => [
                    'title' => $experience->title,
                    'organization' => $experience->organization,
                    'description' => $experience->description,
                    'startDate' => $experience->start_date?->format('Y-m-d'),
                    'endDate' => $experience->end_date?->format('Y-m-d'),
                ])->values(),
                'certifications' => $achievements->whereIn('type', $certificationTypes)->map(fn ($certification) => [
                    'title' => $certification->title,
                    'type' => $certification->type,
                    'organization' => $certification->organization,
                    'description' => $certification->description,
                    'issueDate' => $certification->issue_date?->format('Y-m-d'),
                ])->values(),
                'skills' => $educator->skills->map(fn ($skill) => [
                    'name' => $skill->approvedSkill?->name ?? $skill->custom_name,
                    'level' => $skill->level,
                ])->values(),
                'services' => $educator->services->where('is_active', true)->map(fn ($service) => [
                    'name' => $service->approvedService?->name ?? $service->custom_name,
                    'description' => $service->custom_description,
                ])->values(),
                'achievements' => $achievements->whereNotIn('type', array_merge(['work_experience'], $certificationTypes))->map(fn ($achievement) => [
                    'title' => $achievement->title,
                    'description' => $achievement->description,
                    'organization' => $achievement->organization,
                    'issueDate' => $achievement->issue_date?->format('Y-m-d'),
                ])->values(),
                'socialLinks' => $educator->socialLinks->map(fn ($link) => [
                    'platform' => $link->platform,
                    'url' => $link->url,
                ])->values(),
            ],
        ], msg: 'Public educator profile retrieved successfully');
    }
}
