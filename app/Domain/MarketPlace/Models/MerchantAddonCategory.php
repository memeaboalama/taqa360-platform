<?php

namespace Domain\MarketPlace\Models;

use App\Support\Traits\Models\BelongsToMerchant;
use App\Support\Traits\Models\HasStatus;
use App\Support\Traits\Models\MerchantRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MerchantAddonCategory extends Model
{
    use HasFactory,BelongsToMerchant,MerchantRelation,HasTranslations,HasStatus;
    public $translatable = ['title'];
    protected $table="merchant_service_addons_category";
    protected $fillable=[
        'merchant_id',
        'merchant_service_id',
        'title',
        'status',
        'option_type',
        'min_required',
    ];

    public function addons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantAddon::class,'addon_category_id','id');
    }

    public function service(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantService::class, 'merchant_service_id', 'id');
    }
}
