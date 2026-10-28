<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\CashBack;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CashBackRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(CashBack $model)
    {
        parent::__construct($model);
    }

    public function search(array $cols, string $query) : Collection
    {
        return $this->modal->select($cols)
            ->where('name', 'like', '%' . $query . '%')
            ->get();
    }
}
