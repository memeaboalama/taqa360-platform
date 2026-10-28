<?php

namespace App\Domain\Provider\Models;

use App\Domain\User\Models\User;
use App\Support\Enum\OrderActionEnum;
use App\Support\Enum\StatusEnum;
use App\Support\Enum\VehicleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\SpatialBuilder;

class ProviderOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'provider_id',
        'provider_sub_services_id',
        'provider_vehicle_id',
        'provider_vehicle_plate_number',
        'provider_vehicle_type',
        'payment_type',
        'collected_price_by_provider',
        'total_order',
        'vat',
        'total_after_vat',
        'client_name',
        'client_full_phone',
        'provider_name',
        'company_name',
        'client_location',
        'schedulable',
        'date',
        'from',
        'to',
        'status',
    ];

    protected $casts = [
        'schedulable'               =>  StatusEnum::class,
        'provider_vehicle_type'     =>  VehicleType::class,
        'client_location'           => Point::class,
        'status'                    => OrderActionEnum::class,

    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function provider_towing_order_detail(): HasOne
    {
        return $this->hasOne(ProviderTowingOrderDetail::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function provider_sub_services(): BelongsTo
    {
        return $this->belongsTo(ProviderSubService::class);
    }

    public function order_actions(): HasMany
    {
        return $this->hasMany(OrderAction::class,'provider_order_id','id');
    }

    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }

}
