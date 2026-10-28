<?php

namespace Domain\MarketPlace\Models;

use App\Support\Traits\Models\BelongsToMerchant;
use App\Support\Traits\Models\BelongsToUser;
use App\Support\Traits\Models\MerchantRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchantPhone extends Model
{
    use HasFactory,BelongsToMerchant,MerchantRelation;

    protected $fillable = [
        'merchant_id',
        'phone',
        'dialcode',
        'country_name',
        'country_code',
        'is_default',
        'verified_at',
    ];

    protected $casts = [
        'verified_at'=>'datetime',
    ];

}
