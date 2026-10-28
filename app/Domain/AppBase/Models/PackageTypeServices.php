<?php

namespace App\Domain\AppBase\Models;


use App\Support\Traits\Models\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domain\MarketPlace\Models\Package;
use App\Domain\AppBase\Models\TypeService;


class PackageTypeServices extends Model
{
    use  HasStatus;

    // protected $translatable = ['name'];

    protected $fillable = [
        'package_id',
        'type_service_id',
        'commission',
        'discount_rate',
        'status',
        'desc'
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
    public function type_service(): BelongsTo
    {
        return $this->belongsTo(TypeService::class);
    }
  
}
