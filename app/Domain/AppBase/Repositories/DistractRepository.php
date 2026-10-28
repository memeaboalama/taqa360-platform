<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Location\Distract;
use App\Support\Enum\StatusEnum;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Database\Eloquent\Model;

class DistractRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Distract $model)
    {
        parent::__construct($model);
    }

    public function findByKey(string $key): Model|null
    {
        return $this->modal->query()->where('key', $key)->first();
    }

    public function firstOrCreate(City $city, array $data)
    {
        if(isset($data["state"])){
            $distract = $city->distracts()->where('key', $data["state"])->first();
            if (!$distract) {
                $distract = $city->distracts()->create([
                    'title' => [
                        'en' => $data["state_short"],
                        'ar' => $data["state_short"],
                    ],
                    'key' => $data["state"],
                    'country_id' => $city->country_id,

                ]);
            }

            return $distract;
        }
        return  null;

    }

    public function getByCityId(int $city_id): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->builder(cols: ['id', 'title'], relations: [], condition: ['status' => StatusEnum::ACTIVE])->where('city_id', $city_id)->get();
    }
}
