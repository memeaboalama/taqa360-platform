<?php

namespace App\Domain\AppBase\Models;


use App\Support\Traits\Models\HasStatus;
use Illuminate\Database\Eloquent\Model;
use App\Domain\MarketPlace\Models\Package;
use App\Domain\AppBase\Models\TypeService;
use App\Domain\AppBase\Models\Location\City;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Domain\MarketPlace\Models\MerchantBranch;

class MerchantBranchTypeServices extends Model
{
    use  HasStatus;

    protected $translatable = ['type_service_name'];
    protected $fillable = [
        'type_service_id',
        'merchant_branch_id',
        'city_id',
        'package_id',
        'commission',
        'discount_rate',
        'desc',
        'status',
        'type_service_name'
    ];
   
    protected $appends = ['type_service_name'];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
    public function type_service(): BelongsTo
    {
        return $this->belongsTo(TypeService::class);
    }
    public function merchant_branch(): BelongsTo
    {
        return $this->belongsTo(MerchantBranch::class);
    }
    public function getTypeServiceNameAttribute()
    {
        return $this->type_service->name; 
    }
}
