<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Database\Eloquent\Model;

class CityRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(City $model)
    {
        parent::__construct($model);
    }

    public function findByKey(string $key): Model|null
    {
        return $this->modal->query()->where('key',$key)->first();
    }

    public function firstOrCreate(array $data)
    {
        if(isset($data["city"])){

            $city = $this->findByKey($data["city"]);
            if (!$city) {
                $city = $this->modal->create([
                    'title' => [
                        'en' => $data["city_short"],
                        'ar' => $data["city_short"],
                    ],
                    'key' => $data["city"],
                    'country_id' => Country::query()->first()?->id,
                ]);
            }

            return $city;
        }
       return null;
    }
}
