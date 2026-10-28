<?php

namespace App\Domain\Provider\Repositories\DriversRegisters;

use App\Domain\Provider\Models\DriversRegisters;
use App\Support\Repositories\Main\EloquentMainRepository;

class DriversRegistersRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(DriversRegisters $model)
    {
        parent::__construct($model);
    }
    public function filter($filters = [], array $with = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = $this->modal
            ->with($with)
            ->newQuery();

        if (isset($filters['status']))
            $query->where('status', $filters['status']);

        if (isset($filters['driver_id']))
            $query->where('id', $filters['driver_id']);

        if (isset($filters['network_status']))
        {
            if ($filters['network_status'] == "online")
            {
                $query->where('network_status', 1);
            }elseif ($filters['network_status'] == "offline"){
                $query->where('network_status', 0);
            }
        }
        if (isset($filters['company_id']) && $filters['company_id'] != null)
        {
            $query->whereHas('company', function($q) use($filters){
                $q->where('company_id', $filters['company_id']);
            });
        }
        return $query;
    }

    public function getAllDriversRegistersAsBuilder(array $cols, array $with ,array $conditions = []): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder($cols, $with, $conditions, 'desc');
    }
}

