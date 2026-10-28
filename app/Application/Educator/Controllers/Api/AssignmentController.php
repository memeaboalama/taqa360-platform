<?php

namespace App\Application\Educator\Controllers\Api;

use App\Application\Project\Enums\AssignmentStatus;
use App\Domain\Educator\Resource\AssignmentResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Domain\Project\Models\Assignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    use ApiResponseTraitV2;

    public function fetch(Request $request): JsonResponse
    {
        $educatorId = auth()->id();

        $query = Assignment::with(['task.project'])
            ->where('assigned_to', $educatorId)
            // A cancelled assignment means the admin withdrew it, so the
            // specialist must not see the task in their list any more.
            ->where('status', '!=', AssignmentStatus::CANCELLED->value);

        $type = $request->get('type');
        if ($type === 'invitation') {
            $query->where('assigned_type', 'invitation')
                // The panel hides an accepted invitation (it moves to the
                // assignments tab), so the list must not return it either:
                // otherwise the badge (which excludes it) disagrees with the
                // paginated total and a page can render empty after filtering.
                ->where('status', '!=', AssignmentStatus::ACCEPTED->value);
        } elseif ($type === 'assignment') {
            // This tab means "the work I have actually taken on", but it used to
            // match on assigned_type only, so an invitation that was accepted
            // fell between the two tabs: it left the invitations tab (the panel
            // hides an accepted invitation) and never reached this one, because
            // accepting an invitation does not change its assigned_type. Including
            // accepted invitations here is what puts it back in front of the
            // specialist. Pending and rejected invitations stay in the invitations
            // tab, which is where they are still being decided.
            $query->where(function ($query) {
                $query->whereIn('assigned_type', ['direct', 'from_bid'])
                    ->orWhere(function ($query) {
                        $query->where('assigned_type', 'invitation')
                            ->where('status', AssignmentStatus::ACCEPTED->value);
                    });
            });
        }

        $assignments = $query->orderBy('created_at', 'desc')
            ->paginate((int) $request->get('per_page', 10));

        $invitationsCount = Assignment::where('assigned_to', $educatorId)
            ->where('assigned_type', 'invitation')
            ->where('status', '!=', AssignmentStatus::CANCELLED->value)
            // The panel hides an invitation once it is accepted, so the tab badge
            // must not keep counting it.
            ->where('status', '!=', AssignmentStatus::ACCEPTED->value)
            ->count();
        $assignmentsCount = Assignment::where('assigned_to', $educatorId)
            ->where('status', '!=', AssignmentStatus::CANCELLED->value)
            // Must count exactly what the assignments tab lists, so the badge
            // cannot disagree with the rows underneath it. Same rule as the
            // `$type === 'assignment'` branch above: direct and from_bid work
            // whatever their status, plus an invitation once it is accepted.
            ->where(function ($query) {
                $query->whereIn('assigned_type', ['direct', 'from_bid'])
                    ->orWhere(function ($query) {
                        $query->where('assigned_type', 'invitation')
                            ->where('status', AssignmentStatus::ACCEPTED->value);
                    });
            })
            ->count();

        return $this->responseData([
            'assignments' => AssignmentResource::collection($assignments),
            'counts' => [
                'invitations' => $invitationsCount,
                'assignments' => $assignmentsCount,
            ],
        ], 200, 'Assignments retrieved successfully');
    }

    public function acceptInvitation(Request $request, Assignment $assignment): JsonResponse
    {
        if ($assignment->assigned_to !== auth()->id() || $assignment->status !== AssignmentStatus::PENDING) {
            return $this->responseError([], 403, 'Unauthorized or assignment is not pending.');
        }

        $assignment->update([
            'status' => AssignmentStatus::ACCEPTED,
            'accepted_at' => now(),
        ]);

        return $this->responseData([
            'assignment' => AssignmentResource::make($assignment->load('task.project'))
        ], 200, 'Invitation accepted successfully');
    }

    public function declineInvitation(Request $request, Assignment $assignment): JsonResponse
    {
        if ($assignment->assigned_to !== auth()->id() || $assignment->status !== AssignmentStatus::PENDING) {
            return $this->responseError([], 403, 'Unauthorized or assignment is not pending.');
        }

        // The decline dialog asks the specialist why, so the answer has to be kept:
        // the admin reads it back on the project screen. The column already
        // exists, so this is only filling it in.
        $reason = $request->input('rejection_reason')
            ?? $request->input('rejectionReason')
            ?? $request->input('reason')
            ?? $request->input('note');

        if (! is_string($reason) || trim($reason) === '') {
            return $this->responseError([], 422, 'A rejection reason is required.');
        }

        $assignment->update([
            'status' => AssignmentStatus::REJECTED,
            'rejection_reason' => trim($reason),
        ]);

        return $this->responseData([
            'assignment' => AssignmentResource::make($assignment->load('task.project'))
        ], 200, 'Invitation declined successfully');
    }
}
