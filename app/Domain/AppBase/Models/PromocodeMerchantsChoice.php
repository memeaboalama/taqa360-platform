<?php

namespace App\Domain\AppBase\Models;

use App\Support\Enum\Promocode\PromocodeStatus;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromocodeMerchantsChoice extends Model
{
    use HasFactory;
    protected $table = "promocode_merchants_choice";
    protected $fillable=[
        'promocode_id',
        'merchant_id',
        'status',
    ];



    public function merchant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }
    public function promocode(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Promocode::class, 'merchant_id', 'id');
    }
    
}
