<?php

namespace Domain\MarketPlace\Models;

use App\Support\Traits\Models\MerchantRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MerchantOrderAddon extends Model
{
    use HasFactory, HasTranslations, MerchantRelation;
    public array $translatable = ['addon_name'];
    protected $fillable = [
        'addon_name',
        'addon_total',
        'total_after_vat',
        'merchant_id',
        'user_id',
        'order_id',
        'merchant_branch_id',
        'merchant_service_id',
        'addon_id',
        'order_service_id',


    ];

    public function addon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantAddon::class,'addon_id','id');
    }
}
