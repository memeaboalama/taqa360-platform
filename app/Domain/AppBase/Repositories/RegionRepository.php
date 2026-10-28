<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Location\Region;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Database\Eloquent\Model;

class RegionRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Region $model)
    {
        parent::__construct($model);
    }

    public function findByKey(string $key): Model|null
    {
        return $this->modal->query()->where('key',$key)->first();
    }
}
