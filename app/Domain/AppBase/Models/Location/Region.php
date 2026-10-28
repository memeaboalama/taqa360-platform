<?php

namespace App\Domain\AppBase\Models\Location;

use App\Support\Enum\StatusEnum;
use App\Support\Scopes\OrderByPriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Region extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['title'];
    protected $fillable = ['title','elm_id','status','country_id','priority','key'];
    protected $casts = [
        'status' => StatusEnum::class
    ];
    
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): HasMany
    {
        return $this->hasMany(City::class);
    }

    /**
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new OrderByPriority());
    }
}
