<?php

namespace App\Domain\AppBase\Models\Location;

use App\Support\Enum\StatusEnum;
use App\Support\Scopes\OrderByPriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Distract extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['title'];

    protected $fillable = ['title','status','country_id','city_id','priority','key'];
    protected $casts = [
        'status'    =>  StatusEnum::class
    ];
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new OrderByPriority());
    }
}
