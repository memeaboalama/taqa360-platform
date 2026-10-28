<?php

namespace App\Domain\Educator\Resource\Profile;

use App\Application\Educator\Enums\AcademicDegreeEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademicMajorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->uuid,
            'majorName' => $this->is_custom ? $this->custom_name : ($this->approvedMajor?->name ?? null),
            'isCustom' => (bool)$this->is_custom,
            'institution' => $this->graduation_institution,
            'graduationYear' => $this->graduation_year,
            // 'degree' => $this->degree?->trans(),
            'degree' => $this->degree,

            'documentUrl' => $this->certificateFile?->url ?? null,
            'verificationStatus' => $this->approval_document ? 'VERIFIED' : 'PENDING',
            'rejectionReason' => $this->rejection_reason ?? null,
        ];
    }
}
