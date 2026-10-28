<?php

namespace Domain\MarketPlace\Models;


use App\Domain\AppBase\Models\Setting\Currency;
use App\Domain\MarketPlace\Models\MerchantOrderDetail;
use App\Support\Enum\ActiveEnum;
use App\Support\Traits\Models\BelongsToMerchant;
use App\Support\Traits\Models\HasStatus;
use App\Support\Traits\Models\MerchantRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class MerchantService extends Model
{
    use HasFactory, SoftDeletes, HasTranslations, BelongsToMerchant, MerchantRelation, HasStatus;

    protected $translatable = ['title', 'description'];

    protected $fillable = [
        'merchant_id',
        'currency_id',
        'title',
        'description',
        'default_price',
        'status',
        'updated',
        // 'is_scheduled',
        // 'working_minutes',
        // 'requests_per_hour_for_same_service',

    ];

    protected $casts = [
        'status' => ActiveEnum::class,
    ];

    public function currency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function addon_categories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantAddonCategory::class, 'merchant_service_id', 'id');
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MerchantBranch::class, 'merchant_service_id', 'id');
    }


    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this
            ->belongsToMany(MerchantCategory::class, 'merchant_services_category', 'merchant_service_id', 'merchant_category_id')
            ->withPivot('merchant_id', 'merchant_branch_id')
            ->withTimestamps();
    }


    public function inReview()
    {
        return ($this->updated == "in-review") ? true : false;
    }

    public function scopeGetServiceCategory($query, int $category, int $branch): void
    {

        if (is_null($query->getQuery()->columns)) {

            $query->select('*');
        }
        $query->whereHas('categories', function ($q) use ($category, $branch) {

            $q->where('merchant_categories.id', $category);
            //                ->where('merchant_branch_id',$branch);
        });
    }
}
