<?php

namespace App\Support\Traits\Models;

use Domain\MarketPlace\Models\Merchant;

trait MerchantRelation
{
    public function merchant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Merchant::class,'merchant_id','id');
    }
}
