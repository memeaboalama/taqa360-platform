<?php

namespace App\Domain\AppBase\Models;

use App\Support\Enum\RefundPayment\RefundPaymentStatus;
use Domain\AppBase\Models\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundPayment extends Model
{
    use HasFactory;
    protected $fillable=[
        'refund_payment_id',
        'amount',
        'currency',
        'note',
        'type',
        'payment_id',
        'order_id',
        'user_id',
        'user_name',
        'calculated_on',
    ];

    // protected $casts = [
    //     'status' => RefundPaymentStatus::class,
    // ];

    public function payment(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }
    public function payable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
    
}
