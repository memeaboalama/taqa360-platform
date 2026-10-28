<?php

namespace Domain\MarketPlace\Models;

use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\User\Models\User;
use App\Support\Traits\Models\MerchantRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MerchantOrderService extends Model
{
    use HasFactory, HasTranslations, MerchantRelation;

    public array $translatable = ['service_name'];
    protected $fillable = [
        'service_name',
        'service_total',
        'vat',
        'total_after_vat',
        'merchant_id',
        'user_id',
        'order_id',
        'merchant_branch_id',
        'merchant_service_id',
    ];

    public function user() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function order() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantOrder::class,'order_id');
    }

    public function addons() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantOrderAddon::class,'order_service_id');
    }

    public function service() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantService::class,'merchant_service_id');
    }

}
