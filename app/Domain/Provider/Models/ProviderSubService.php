<?php

namespace App\Domain\Provider\Models;

use App\Domain\AppBase\Models\Category\MainCategory;
use App\Domain\Towing\Models\TowingType;
use App\Support\Enum\StatusEnum;
use App\Support\Enum\ServiceStrategy;
use App\Support\Enum\TowingDomain;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class ProviderSubService extends Model
{
    use HasFactory,HasTranslations;

    protected $translatable = ['title','description'];

    protected $fillable = [
        'provider_main_service_id',
        'provider_category_id',
        'main_category_id',
        'title',
        'description',
        'price',
        'schedulable',
        'increasable',
        'strategy',
        'towing_domain',
        'towing_type_id',
        'towing_open_price',
        'towing_price_per_km',
        'towing_price_per_minute',
        'status',
    ];

    protected $casts = [
        'towing_domain'  =>  TowingDomain::class,
        'strategy'       =>  ServiceStrategy::class,
        'schedulable'    =>  StatusEnum::class,
        'increasable'    =>  StatusEnum::class,
        'status'         =>  StatusEnum::class,
    ];

    public function main_category(): BelongsTo
    {
        return $this->belongsTo(MainCategory::class);
    }
    public function provider_category(): BelongsTo
    {
        return $this->belongsTo(ProviderCategory::class);
    }

    public function provider_main_service(): BelongsTo
    {
        return $this->belongsTo(ProviderMainService::class);
    }
    public function towing_type(): BelongsTo
    {
        return $this->belongsTo(TowingType::class);
    }

}
