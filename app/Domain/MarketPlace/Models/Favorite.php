<?php

namespace App\Domain\MarketPlace\Models;

use App\Domain\User\Models\User;
use App\Support\Traits\Models\MerchantRelation;
use Domain\MarketPlace\Models\Item;
use Domain\MarketPlace\Models\MerchantBranch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory,MerchantRelation;

    protected $fillable = [
        'item_id',
        'user_id',
        'merchant_id',
        'merchant_branch_id',
    ];


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantBranch::class,'merchant_branch_id','id');
    }

    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class,'item_id','id');
    }
}
