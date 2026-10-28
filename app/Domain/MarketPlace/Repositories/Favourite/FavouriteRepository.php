<?php

namespace App\Domain\MarketPlace\Repositories\Favourite;

use App\Domain\MarketPlace\Models\Favorite;
use App\Support\Repositories\Main\EloquentMainRepository;

class FavouriteRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Favorite $model)
    {
        parent::__construct($model);
    }

    public function fetch(array $cols = ['*'], array $relations = [], array $conditions = [])
    {
        return $this->modal::select($cols)->with($relations)->where($conditions)->get();
    }
}
