<?php

namespace App\Domain\AppBase\Models;

use App\Support\Enum\Promocode\PromocodeStatus;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBackMerchantsChoice extends Model
{
    use HasFactory;
    protected $table = "cashback_merchants_choice";
    protected $fillable = [
        'cashback_id',
        'merchant_id',
        'status',
    ];



    public function merchant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }
    public function cashback(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CashBack::class, 'cashback_id', 'id');
    }
}
