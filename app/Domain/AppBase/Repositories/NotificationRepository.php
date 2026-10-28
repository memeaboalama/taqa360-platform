<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\NotificationNew;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class NotificationRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(NotificationNew $model)
    {
        parent::__construct($model);
    }
    public function search(array $cols, string $query) : Collection
    {
        return $this->modal->select($cols)
            ->where('title', 'like', '%' . $query . '%')
            ->orWhere('body', 'like', '%' . $query . '%')
            ->get();
    }

    public function filterNotification(array $filters = [], array $with = [], array $cols = [], array $conditions = []): \Illuminate\Database\Eloquent\Builder
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


        if (isset($filters['admin_id']))
            $query->where('admin_id', $filters['admin_id']);

        if (isset($filters['title']))
            $query->where('title', $filters['title']);



        if (isset($filters['body']))
            $query->where('body', $filters['body']);



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
