<?php

namespace Domain\EducationalRequest\Repositories;

use App\Application\Educational\Enums\EducationalRequestStatus;
use Illuminate\Database\Eloquent\Model;
use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\EducationalRequest\Models\EducationalRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
class EducationalRequestRepository extends EloquentMainRepository
{
    public function __construct(EducationalRequest $model)
    {
        parent::__construct($model);
    }

    public function getUserRequests(int $userId, array $filters = []): LengthAwarePaginator
    {
        return $this->model
            ->where('user_id', $userId)
            ->filter($filters)
            ->with(['academicMajor', 'reviewer'])
            ->paginate();
    }

    public function getPendingRequests(array $filters = []): LengthAwarePaginator
    {
        return $this->model
            ->where('status', EducationalRequestStatus::PENDING)
            ->filter($filters)
            ->with(['user', 'academicMajor'])
            ->paginate();
    }

}
