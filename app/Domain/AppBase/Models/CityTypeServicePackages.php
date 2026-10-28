<?php

namespace App\Domain\AppBase\Models;


use App\Support\Traits\Models\HasStatus;
use Illuminate\Database\Eloquent\Model;
use App\Domain\MarketPlace\Models\Package;
use App\Domain\AppBase\Models\TypeService;
use App\Domain\AppBase\Models\Location\City;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CityTypeServicePackages extends Model
{
    use  HasStatus;


    protected $fillable = [
        'type_service_id',
        'city_id',
        'package_id',
        'commission',
        'discount_rate',
        'desc',
        'status'
    ];
   

    protected function packageTitle(): Attribute
    {
        return new Attribute(
            get: fn() => $this->package()->getTranslations('title')
        );
    }
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
    public function type_service(): BelongsTo
    {
        return $this->belongsTo(TypeService::class);
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

}
