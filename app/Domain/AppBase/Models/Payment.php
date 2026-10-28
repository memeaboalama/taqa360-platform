<?php

namespace Domain\AppBase\Models;

use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable=[
      'payment_id',
      'status',
      'amount',
      'fee',
      'currency',
      'refunded',
      'refundedAt',
      'captured',
      'capturedAt',
      'voidedAt',
      'description',
      'amountFormat',
      'feeFormat',
      'refundedFormat',
      'capturedFormat',
      'invoiceId',
      'ip',
      'callbackUrl',
      'createdAt',
      'updatedAt',
      'metadata',
      'source_type',
      'source_company',
      'source_name',
      'source_number',
      'source_message',
      'source_transaction_url',
      'source_gateway_id',
      'source_reference_number',
      'user_id',
      'channel',

    ];


    public function payable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
