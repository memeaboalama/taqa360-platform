<?php

namespace App\Domain\Provider\Models;

use App\Support\Enum\OrderActionEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderAction extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'provider_order_id',
        'provider_id',
        'action',
    ];
    protected $casts = [
        'action'   =>   OrderActionEnum::class
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function provider_order(): BelongsTo
    {
        return $this->belongsTo(ProviderOrder::class);
    }
}
