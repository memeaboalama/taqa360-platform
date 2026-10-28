<?php

namespace App\Support\Repositories\Spatie;

use App\Support\Repositories\Main\EloquentMainRepository;
use App\Support\Services\Auth\Guard;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class RoleRepository extends EloquentMainRepository
{
    public array $_UNIQUE_FILTERS;
    public $guard;
    public function __construct(Role $model)
    {
        $this->guard = Guard::get();
        parent::__construct($model);
    }

    public function getAsBuilder(array $cols, array $with = [], array $filters = []): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder($cols, $with, [], 'desc')->where($filters);
    }

    public function store(array $data): ?Model
    {
        return $this->modal::create($data);
    }

    public function findByCols(array $cols,array $with = [],array $select = ['*']): ?Model
    {
        return $this->modal::with($with)->select($select)->where($cols)->first();
    }


}
