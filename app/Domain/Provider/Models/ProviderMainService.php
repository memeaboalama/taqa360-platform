<?php

namespace App\Domain\Provider\Models;

use App\Domain\AppBase\Models\Category\MainCategory;
use App\Support\Enum\StatusEnum;
use App\Support\Traits\Models\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class ProviderMainService extends Model
{
    use HasFactory,HasTranslations,HasStatus;

    protected $translatable = ['title','description'];

    protected $fillable = [
        'title',
        'description',
        'starter_price',
        'provider_category_id',
        'main_category_id',
        'status',
    ];

    protected $casts = [
        'status'    =>  StatusEnum::class
    ];

    public function main_category(): BelongsTo
    {
        return $this->belongsTo(MainCategory::class);
    }
    public function provider_category(): BelongsTo
    {
        return $this->belongsTo(ProviderCategory::class);
    }

    public function sub_services (): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProviderSubService::class);

    }

}
