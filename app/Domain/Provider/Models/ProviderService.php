<?php

namespace App\Domain\Provider\Models;

use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProviderService extends Model
{
    use HasFactory;


    protected $fillable = [
        'provider_category_id',
        'provider_main_service_id',
        'provider_sub_service_id',
        'providerable_type',
        'providerable_id',
        'status',
    ];

    protected $casts = [
        'status'    =>  StatusEnum::class
    ];

    public function provider_category(): BelongsTo
    {
        return $this->belongsTo(ProviderCategory::class);
    }

    public function provider_main_service(): BelongsTo
    {
        return $this->belongsTo(ProviderMainService::class);
    }

    public function provider_sub_service(): BelongsTo
    {
        return $this->belongsTo(ProviderSubService::class);
    }

    public function providerable(): MorphTo
    {
        return $this->morphTo();
    }

}
