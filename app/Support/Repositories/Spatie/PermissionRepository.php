<?php

namespace App\Support\Repositories\Spatie;

use App\Support\Repositories\Main\EloquentMainRepository;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends EloquentMainRepository
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    public function get($filters)
    {
        $identifier = (LaravelLocalization::getCurrentLocale() == 'ar') ? '_'. LaravelLocalization::getCurrentLocale() : "";
        return $this->modal
            ->where($filters)
            ->get()
            ->groupBy('group_name' .$identifier)->toArray();
    }

    public function getIds($class)
    {
        return $this->modal->where('team_type', $class)->pluck('id','id')->all();
    }
}
