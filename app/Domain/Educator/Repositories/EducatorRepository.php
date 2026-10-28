<?php

namespace Domain\Educator\Repositories;

use Domain\Educator\Models\Educator;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Database\Eloquent\Collection;

class EducatorRepository extends EloquentMainRepository
{
    public function __construct(Educator $model)
    {
        parent::__construct($model);
    }

    public function findForProfileBuilder(int $educatorId): ?Educator
    {
        return $this->modal->with([
            'profile',
            'academicMajors.approvedMajor',
            'academicMajors.certificateFile',
            'languages',
            'services.approvedService',
            'skills.approvedSkill',
            'achievements.files',
            'bios',
            'socialLinks',
            'bankInfo',
            'files'
        ])->find($educatorId);
    }

    public function filterAndPaginate(array $filters = [], array $with = [], int $per_page = 10)
    {
        $query = $this->modal->newQuery();

        if (isset($filters['search']) && $filters['search'] != null) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if (isset($filters['status']) && $filters['status'] != null) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['country_id'] ) && $filters['country_id'] != null) {
            $query->whereHas('profile', function ($q) use ($filters) {
                $q->where('country_id', $filters['country_id']);
            });
        }

        if (!empty($with)) {
            $query->with($with);
        }

        return $query->latest()->paginate($per_page);
    }


    public function filter(array $filters = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = $this->modal->newQuery();

        if (isset($filters['search']) && $filters['search'] != null) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if (isset($filters['status']) && $filters['status'] != null) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['educator_id']) && $filters['educator_id'] != null) {
            $query->where('id', $filters['educator_id']);
        }

        if (isset($filters['country_id']) && $filters['country_id'] != null) {
            $query->whereHas('profile', function ($q) use ($filters) {
                $q->where('country_id', $filters['country_id']);
            });
        }
        return $query;
    }


    public function getEducatorTotalOrderReport(int $educatorId): array
    {
        return [
            'total_orders' => 100,
            'completed_orders' => 80,
            'pending_orders' => 20,
        ];
    }


    public function getAllEducatorsAsBuilder(array $cols, array $with, array $conditions = []): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder($cols, $with, $conditions, 'desc');
    }

    public function search(array $cols, string $query) : Collection
    {
        return $this->modal->select($cols)
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('phone', 'like', '%' . $query . '%')
            ->get();
    }


}
