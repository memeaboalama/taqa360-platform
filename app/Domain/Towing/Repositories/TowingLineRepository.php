<?php

namespace App\Domain\Towing\Repositories;

use App\Domain\Towing\Models\TowingCity;
use App\Domain\Towing\Models\TowingLine;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Support\Repositories\Main\EloquentMainRepository;

class TowingLineRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(TowingLine $model)
    {
        parent::__construct($model);
    }

}
