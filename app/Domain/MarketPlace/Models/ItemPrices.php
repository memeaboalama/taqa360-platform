<?php

namespace App\Domain\MarketPlace\Models;


use App\Support\Traits\Models\HasStatus;

use Illuminate\Database\Eloquent\Model;

use App\Domain\AppBase\Models\TypeService;
use Domain\MarketPlace\Models\Item;
use App\Support\Enum\ActiveEnum;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPrices extends Model
{
    use  HasStatus;
    protected $translatable = ['type_service_name'];
    protected $fillable = [
        'item_id',
        'type_service_id',
        'price',
        'status',
        'type_service_name',
    ];
    protected $appends = ['type_service_name'];
    protected $casts = [
        'status' => ActiveEnum::class,
    ];
    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function type_service(): BelongsTo
    {
        return $this->belongsTo(TypeService::class);
    }
    public function getTypeServiceNameAttribute()
    {
        return $this->type_service->name;
    }
}
