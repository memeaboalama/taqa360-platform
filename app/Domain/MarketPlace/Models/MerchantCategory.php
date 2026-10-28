<?php

namespace Domain\MarketPlace\Models;

use App\Support\Traits\Models\BelongsToMerchant;
use App\Support\Traits\Models\HasStatus;
use App\Support\Traits\Models\MerchantRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MerchantCategory extends Model
{
    use HasFactory, HasTranslations, BelongsToMerchant, MerchantRelation,HasStatus;

    protected $translatable = ['title'];
    protected $fillable = ["title", 'status', 'priority', 'merchant_id', 'sort'];


    public function services(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(MerchantService::class,'merchant_services_category','merchant_category_id','merchant_service_id')
            ->withPivot('merchant_id','merchant_branch_id')
            ->withTimestamps();
    }

    public function items()
    {
        return $this->hasMany(Item::class,  'category_id', 'id');
    }

    public function branches(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(MerchantBranch::class,'merchant_branch_categories','merchant_category_id','merchant_branch_id')
            ->withPivot('merchant_id','status')
            ->withTimestamps();
    }
}
