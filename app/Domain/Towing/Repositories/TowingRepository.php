<?php

namespace Domain\Towing\Repositories;

use App\Domain\Towing\Models\TowingCity;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Support\Repositories\Main\EloquentMainRepository;

class TowingRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(TowingCity $model)
    {
        parent::__construct($model);
    }
}
