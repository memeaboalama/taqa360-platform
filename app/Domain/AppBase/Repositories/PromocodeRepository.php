<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\Promocode;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PromocodeRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Promocode $model)
    {
        parent::__construct($model);
    }

    public function search(array $cols, string $query) : Collection
    {
        return $this->modal->select($cols)
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('phone', 'like', '%' . $query . '%')
            ->get();
    }

    public function filterPromocodes(array $filters = [], array $with = [], array $cols = [], array $conditions = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = $this->modal
            ->with($with)
            ->select($cols)
            ->where($conditions)
            ->newQuery();
        return $this->applyFiltersV2($query, $filters);
    }

    protected function applyFilters(array $filters = [], $query): \Illuminate\Database\Eloquent\Builder
    {
        return $this->applyFiltersV2($query, $filters);
    }
    protected function applyFiltersV2($query, array $filters = []): \Illuminate\Database\Eloquent\Builder
    {


        if (isset($filters['status']))
            $query->where('status', $filters['status']);

        if (isset($filters['type']))
            $query->where('type', $filters['type']);



        if (isset($filters['code']) && $filters['code'] != null)
            $query->where('code', $filters['code']);



        if (isset($filters['date_condition']))
        {
            if ($filters['date_condition'] == "equal")
                $query->whereDate('created_at', $filters['date']);

            if ($filters['date_condition'] == "gt")
                $query->whereDate('created_at','>=', $filters['date']);

            if ($filters['date_condition'] == "between")
                $query->whereDate('created_at','>=',$filters['date'])
                    ->whereDate('created_at','<=',$filters['to_date']);
        }

        return $query;
    }
}
