<?php

namespace App\Domain\Provider\Repositories;

use App\Domain\Provider\Models\ProviderSubService;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Database\Eloquent\Model;

class ProviderSubServiceRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(ProviderSubService $model)
    {
        parent::__construct($model);
    }

    public function find(array $cols, array $condition = []): ?Model
    {
        return $this->modal::select($cols)->where($condition)->first();
    }
}
