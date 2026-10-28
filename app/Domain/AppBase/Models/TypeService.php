<?php

namespace App\Domain\AppBase\Models;


use App\Support\Traits\Models\HasStatus;
use Domain\MarketPlace\Models\MerchantBranch;
use Illuminate\Database\Eloquent\Model;

class TypeService extends Model
{
    use  HasStatus;

    protected $translatable = ['name'];

    protected $fillable = [
        'name',
        'status',
        'desc'
    ];
    public function branchs(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(MerchantBranch::class, 'merchant_branch_type_services', 'type_service_id', 'merchant_branch_id');
    }
    public function items(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\Domain\MarketPlace\Models\Item::class, 'item_prices', 'type_service_id', 'item_id');
    }
}
