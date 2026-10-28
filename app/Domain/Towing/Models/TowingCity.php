<?php

namespace App\Domain\Towing\Models;

use App\Support\Enum\StatusEnum;
use App\Support\Scopes\OrderByPriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\SpatialBuilder;
use Spatie\Translatable\HasTranslations;

class TowingCity extends Model
{
    use HasFactory,HasTranslations;

    protected $translatable = ['title'];

    protected $fillable = [
        'title',
        'location',
        'priority',
        'can_pickup',
        'can_dropoff',
    ];

    protected $casts = [
        'location'      => Point::class,
        'can_pickup'    =>  StatusEnum::class,
        'can_dropoff'   =>  StatusEnum::class,
    ];

    public function towing_lines(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TowingLine::class,'from_towing_city_id','id');
    }
    protected static function booted(): void
    {
        static::addGlobalScope(new OrderByPriority());
    }
}
