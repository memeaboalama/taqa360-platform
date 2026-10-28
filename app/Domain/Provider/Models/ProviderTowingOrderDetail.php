<?php

namespace App\Domain\Provider\Models;

use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MatanYadaev\EloquentSpatial\Objects\Point;

class ProviderTowingOrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider_order_id',
        'towing_type_title',
        'pickup_address',
        'dropoff_address',
        'pickup_address_title',
        'dropoff_address_title',
        'final_drop_off_address',
        'start_picking_at',
        'dropoff_at',
        'price_per_km',
        'pricer_per_minute',
        'total_distance_in_km',
        'total_time_in_minutes',
        'total_price',
    ];

    protected $casts = [
        'start_picking_at'  =>  'datetime',
        'dropoff_at'        =>  'datetime',
        'pickup_address'    =>   Point::class,
        'dropoff_address'   =>   Point::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function provider_order(): BelongsTo
    {
        return $this->belongsTo(ProviderOrder::class);
    }
}
