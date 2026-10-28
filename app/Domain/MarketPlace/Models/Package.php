<?php

namespace App\Domain\MarketPlace\Models;

use App\Support\Enum\StatusEnum;
use App\Support\Traits\Models\MerchantRelation;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Package extends Model
{
    use HasFactory, MerchantRelation, HasTranslations;

    protected array $translatable = ['title','description'];

    protected $fillable = [
        'title',
        'description',
        'commission',
        'terms_en',
        'terms_ar',
        'status',
    ];

    protected $casts = [
        'status' => StatusEnum::class
    ];
    public function type_services()
    {
        return $this->belongsToMany(\App\Domain\AppBase\Models\TypeService::class, 'package_type_services', 'package_id', 'type_service_id')->withPivot('commission', 'discount_rate', 'desc', 'status');
    }
    public function merchants(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Merchant::class,'merchant_packages','package_id','merchant_id');
    }
}
