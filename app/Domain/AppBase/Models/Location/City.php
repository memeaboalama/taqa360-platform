<?php

namespace App\Domain\AppBase\Models\Location;

use App\Domain\AppBase\Models\CityTypeServicePackages;
use App\Support\Enum\StatusEnum;
use App\Support\Scopes\OrderByPriority;
use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasUuid;

    public $translatable = ['title'];
    protected $fillable = ['title','status','elm_id','region_id','country_id','priority','key'];
    protected $casts = [
        'status'    =>  StatusEnum::class
    ];
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function distracts(): HasMany
    {
        return $this->hasMany(Distract::class);
    }
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function cityTypeServicePackages(): HasMany
    {
        return $this->hasMany(CityTypeServicePackages::class);
    }


    /**
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new OrderByPriority());
    }


}
