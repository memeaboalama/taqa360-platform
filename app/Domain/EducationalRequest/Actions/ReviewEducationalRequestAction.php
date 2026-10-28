<?php

namespace App\Domain\EducationalRequest\Actions;

use App\Application\Educational\Enums\EducationalRequestStatus;
use Domain\EducationalRequest\Models\EducationalRequest;
use Domain\Admin\Models\Admin;

class ReviewEducationalRequestAction
{
    public function handle(EducationalRequest $request, Admin $admin, string $status, ?string $rejectionReason = null): EducationalRequest
    {
        $request->update([
            'status' => $status,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'rejection_reason' => ($status === EducationalRequestStatus::REJECTED) ? $rejectionReason : null
        ]);

        if ($status === EducationalRequestStatus::APPROVED) {
            $this->createProjectFromRequest($request);
        }

        return $request->fresh();
    }

    protected function createProjectFromRequest(EducationalRequest $request)
    {
    }
}
