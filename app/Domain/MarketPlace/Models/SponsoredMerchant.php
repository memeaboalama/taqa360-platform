<?php

namespace Domain\MarketPlace\Models;

use App\Support\Traits\Models\BelongsToMerchant;
use App\Support\Traits\Models\MerchantRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SponsoredMerchant extends Model
{
    use HasFactory, MerchantRelation, BelongsToMerchant, HasTranslations;

    public $translatable = ['title', 'address_name'];
    protected $fillable = [
        'merchant_id',
        'title',
        'address_name',
        'latitude',
        'longitude',
    ];

}
