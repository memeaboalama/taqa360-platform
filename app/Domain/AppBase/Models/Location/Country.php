<?php

namespace App\Domain\AppBase\Models\Location;

use App\Domain\AppBase\Models\Setting\Currency;
use App\Support\Enum\StatusEnum;
use App\Support\Scopes\OrderByPriority;
use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Country extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasUuid;

    public $translatable = ['title'];

    protected $fillable = ['title', 'iso', 'status', 'currency_id', 'vat', 'priority'];
    protected $casts = [
        'status' => StatusEnum::class
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function distracts(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Distract::class, City::class);
    }


    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }

    /**
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new OrderByPriority());
    }

}
