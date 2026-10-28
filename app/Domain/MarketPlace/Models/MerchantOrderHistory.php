<?php

namespace App\Domain\MarketPlace\Models;

use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Support\Traits\Models\HasNewFactory;
use Domain\Driver\Models\Driver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchantOrderHistory extends Model
{
    use HasNewFactory,SoftDeletes;
    use HasEvents;

    protected $table='merchant_order_history';
    protected $fillable = [
      'order_id',
      'driver_id',
      'action',
      'reason',
      'changeable_type',
      'changeable_id',
    ];


    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantOrder::class,'order_id','id');
    }

    

    public function driver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Driver::class,'driver_id','id');
    }

    public function changeable()
    {
        return $this->morphTo();
    }
    protected function getChangeableTypeAttribute($value)
    {
        foreach (['Admin', 'MerchantEmployee', 'Driver', 'User'] as $type) {
            if (str_contains($value, $type)) {
                return $type;
            }
        }
        return $value;
    }
}
