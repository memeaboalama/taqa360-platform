<?php

namespace Domain\MarketPlace\Models;


use App\Domain\AppBase\Models\GeneralSetting;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\AppBase\Models\Location\Region;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Support\Enum\StatusEnum;
use App\Support\Traits\Models\BelongsToMerchant;
use App\Support\Traits\Models\MerchantRelation;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;
use tiagomichaelsousa\LaravelFilters\Traits\Filterable;

class MerchantBranch extends Model
{
    use HasFactory, MerchantRelation, BelongsToMerchant, HasTranslations, SoftDeletes, Filterable;

    public array $translatable = ['title', 'description', 'address_name'];
    protected $fillable = [
        'merchant_id',
        'manager_id',
        'merchant_service_id',
        'package_id',
        'title',
        'description',
        'type',
        'accept_multi_requests_at_same_time',
        'auto_accept',
        'has_scheduled_services',
        'address_name',
        'latitude',
        'longitude',
        'region_id',
        'city_id',
        'distract_id',
        'requests_per_hour_for_same_service',
        'slot',
        'distance',
        'isActive',
        'status',
        'to',
        'meridiem',
        'cover_image',
    ];


    //    /**
    //     * The attributes that should be cast.
    //     *
    //     * @var array
    //     */
    //    protected $attributes = [
    //        'title' => [
    //            'en' => null,
    //            'ar' => null
    //        ],
    //
    //        'description' => [
    //            'en' => null,
    //            'ar' => null
    //        ],
    //
    //
    //        'address_name' => [
    //            'en' => null,
    //            'ar' => null
    //        ],
    //    ];

    // public function merchant_branch_type_services() //: \Illuminate\Database\Eloquent\Relations\BelongsToMany
    // {
    //     return $this->belongsToMany(\App\Domain\AppBase\Models\MerchantBranchTypeServices::class, 'merchant_branch_type_services', 'merchant_branch_id', 'type_service_id')->withPivot('package_id', 'desc', 'status');
    // }
    public function merchant_branch_type_services(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        // return $this->hasMany(\App\Domain\AppBase\Models\MerchantBranchTypeServices::class, 'merchant_branch_type_services', 'merchant_branch_id', 'type_service_id');//->withPivot('type_service_id');
        return $this->hasMany(\App\Domain\AppBase\Models\MerchantBranchTypeServices::class, 'merchant_branch_id', 'id'); //->withPivot('type_service_id');
    }
    public function typeServices(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Domain\AppBase\Models\TypeService::class, 'merchant_branch_type_services', 'merchant_branch_id', 'type_service_id'); //->withPivot('type_service_id');
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(MerchantEmployee::class, 'merchant_branch_employees', 'merchant_branch_id', 'merchant_employee_id')
            ->withPivot('merchant_employee_id');
    }
    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function merchant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }

    public function distract(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Distract::class, 'distract_id', 'id');
    }

    public function menu(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantService::class, 'merchant_service_id', 'id');
    }


    public function manager(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantEmployee::class, 'manager_id', 'id');
    }

    public function merchantCategories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(MerchantCategory::class, 'merchant_branch_categories', 'merchant_branch_id', 'merchant_category_id')
            ->withPivot('merchant_id', 'status')
            ->withTimestamps();
    }

    public function workDays(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantBranchWorkday::class, 'merchant_branch_id', 'id');
    }
    public function workDaysHour(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantBranchWorkdayHour::class, 'merchant_branch_id', 'id');
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantOrder::class, 'merchant_branch_id', 'id');
    }

    public function branchActive(): bool
    {
        $isActive = $this->isActive;
        return $isActive == "active" ? true : false;
    }

    public function getCoverAttribute()
    {
        return ($this->cover_image) ? Storage::disk('fileDisk')->url($this?->cover_image) : "";
    }

    public function scopeJoining($query): void
    {
        $query->join('merchants', 'merchants.id', '=', 'merchant_branches.merchant_id');
    }

    public function scopeGetIntDistanceTo($query, array $coordinates): void
    {

        if (is_null($query->getQuery()->columns)) {

            $query->select('*');
        }
        $query->selectRaw('CAST(ST_DISTANCE(
                ST_SRID(point(longitude,latitude),4326), ST_SRID(point(?,?),4326))
                as UNSIGNED) as distance', $coordinates)
            ->having('distance', '<=', (GeneralSetting::first()?->restaurant_search_capacity * 1000));
    }

    public function scopeApplySorting($query, array $sorting = [], array $geo = []): void
    {
        if (in_array("rating", $sorting)) {
            $query->join('merchant_orders', 'merchant_orders.merchant_id', '=', 'merchants.id')
                ->selectRaw('AVG(merchant_orders.rate) AS rating')
                ->where('merchant_orders.rate', "!=", 0)
                ->orderBy('rating', "DESC");
        }

        if (in_array("delivery", $sorting) || in_array("price", $sorting)) {
            # 0.6 is equivalent to 100/60  -> 100 km/H for speed -> 60 min for casting to minutes
            $query->join('merchant_services_category', function ($join) {
                $join->on('merchant_branches.merchant_service_id', '=', 'merchant_services_category.merchant_service_id')
                    ->join('items', function ($sub) {
                        $sub->on('merchant_services_category.merchant_category_id', '=', 'items.category_id');
                    });
            })
                ->when(in_array("delivery", $sorting), function ($qu) use ($geo) {
                    return $qu->selectRaw('ROUND((CAST(ST_DISTANCE(
                          ST_SRID(point(merchant_branches.longitude,merchant_branches.latitude),4326), ST_SRID(point(?,?),4326))
                          as UNSIGNED) / 1000 * 0.6) + AVG(items.preparing_time), 0) as delivery_time', $geo)
                        ->orderBy('delivery_time', "ASC");
                })
                ->when(in_array("price", $sorting), function ($qu) {
                    return $qu->selectRaw('AVG(items.default_price) as avg_price')->orderBy('avg_price', "ASC");
                });
        }
    }


    public function scopeGetDistanceTo($query, array $coordinates): void
    {

        if (is_null($query->getQuery()->columns)) {

            $query->select('*');
        }
        //        $query->selectRaw('ST_DISTANCE(
        //              ST_SRID(point(longitude,latitude),4326),
        //              ST_SRID(point(?,?),4326)
        //             ) as distance', $coordinates);
        // $query->selectRaw('CAST(ST_DISTANCE(
        //           ST_SRID(point(longitude,latitude),4326), ST_SRID(point(?,?),4326))
        //           as UNSIGNED) as distance', $coordinates);

        $query->selectRaw('CAST(ST_Distance_Sphere(
            point(longitude, latitude),
            point(?, ?)
          ) as UNSIGNED) as distance', $coordinates);
    }



    public function scopeWithinDistanceTo($query, array $coordinates, $distance): void
    {

        $query->whereRaw('ST_Distance(
               ST_SRID(point(longitude,latitude),4326),
                ST_SRID(Point(?, ?), 4326)
            ) <= ?', [...$coordinates, $distance]);
    }

    protected function deliveryFee(): Attribute
    {
        $setting = GeneralSetting::query()->latest()?->first();
        $distanceUserRestaurant = floor($this->distance / 1000);
        if ($distanceUserRestaurant <= $setting?->delivery_km) {
            $delivery_fees = $setting?->delivery_fees;
        } else {
            $diff = floor($distanceUserRestaurant - $setting?->delivery_km);
            $extraFees = $diff * $setting?->extra_km_fees;
            $delivery_fees = $setting?->delivery_fees + $extraFees;
        }

        return new Attribute(
            get: fn() => $delivery_fees
        );
    }

    protected function deliveryTime(): Attribute
    {
        # 0.6 is equivalent to 100/60  -> 100 km/H for speed -> 60 min for casting to minutes
        $transmissionTime = $this?->distance / 1000 * 1;
        if ($transmissionTime > 60)
            $transmissionTime = 60;

        $total = $this->merchant?->avgPreparingTime + $transmissionTime;
        if ($total < 45)
            $total = 45;
        return new Attribute(
            get: fn() => round($total, 0)
        );
    }


    public function getDeliveryDistanceAttribute()
    {
        $lat = resolve('lat');
        $long = resolve('long');
        if ($lat && $long) {
            $location = [$long, $lat];
            $distance = $this->getDistanceTo($location)->first()?->distance;

            if ($distance) return round($distance / 1000, 2);
            return 0;
        }

        return null;
    }
}
