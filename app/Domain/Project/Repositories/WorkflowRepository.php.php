<?php

namespace Domain\Project\Repositories;

use App\Application\Project\Enums\WorkflowStatus;
use App\Application\Project\Enums\WorkflowType;
use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\Project\Models\Workflow;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class WorkflowRepository extends EloquentMainRepository
{
    public function __construct(Workflow $model)
    {
        parent::__construct($model);
    }

    public function getPublicTendersForEducator(int $educatorId, array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model
            ->with(['workable'])
            ->where('type', WorkflowType::PUBLIC_TENDER)
            ->where('status', WorkflowStatus::OPEN_FOR_BIDS)
            // Exclude tenders the educator has already bid on
            ->whereDoesntHave('bids', function ($q) use ($educatorId) {
                $q->where('educator_id', $educatorId);
            });

        // Apply filters for searching (e.g., by title, budget range)
        if (isset($filters['search'])) {
            $query->whereHasMorph('workable', '*', function (Builder $query) use ($filters) {
                $query->where('title', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function getAssignedWorkflowsForEducator(int $educatorId, array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model
            ->with(['workable'])
            ->whereHas('assignment', function ($q) use ($educatorId) {
                $q->where('educator_id', $educatorId);
            });

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest('assignments.created_at')->paginate($perPage);
    }
}
