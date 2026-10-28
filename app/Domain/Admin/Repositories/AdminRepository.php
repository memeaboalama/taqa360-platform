<?php

namespace Domain\Admin\Repositories;

use Domain\Admin\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Support\Facades\Auth;

class AdminRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Admin $model)
    {
        parent::__construct($model);
    }

    public function getEmployees(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder(['*'], [], ['type' => 'employee'], 'desc')->where('id', '!=', Auth::id());
    }

    public function updateEmployee(int $id, array $data): ?Model
    {
        return $this->update($id, $data);
    }
}
