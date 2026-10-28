<?php

namespace Domain\MarketPlace\Models;

use App\Support\Traits\Models\BelongsToMerchant;
use App\Support\Traits\Models\HasStatus;
use App\Support\Traits\Models\MerchantRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MerchantAddon extends Model
{
    use HasFactory,BelongsToMerchant,MerchantRelation,HasTranslations,HasStatus;
    public $translatable = ['title'];
    protected $table="merchant_service_addons";
    protected $fillable=[
      'merchant_id',
      'merchant_service_id',
      'addon_category_id',
      'title',
      'status',
      'price',
      'working_minutes',

    ];
}
