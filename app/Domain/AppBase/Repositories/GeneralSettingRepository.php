<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\GeneralSetting;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Database\Eloquent\Model;

class GeneralSettingRepository extends EloquentMainRepository
{
    public function __construct(GeneralSetting $model)
    {
        parent::__construct($model);
    }

    public function selectAndFind(string $key, array $cols = []): Model|null
    {
        return $this->modal->query()->select($cols)->where('id',$key)->first();
    }
}
