<?php

namespace App\Domain\MarketPlace\Models;

use App\Support\Traits\Models\HasNewFactory;
use Domain\Driver\Models\Driver;
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Illuminate\Database\Eloquent\Model;

class DriverHistory extends Model
{
    use HasNewFactory;
    use HasEvents;

    protected $table='driver_history';
    protected $fillable = [
      'driver_id',
      'action',
    ];

    public function driver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Driver::class,'driver_id','id');
    }

    public function changeable()
    {
        return $this->morphTo();
    }

}
