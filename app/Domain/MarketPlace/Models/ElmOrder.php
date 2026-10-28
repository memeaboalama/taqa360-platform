<?php

namespace Domain\MarketPlace\Models;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\MerchantOrder;
use Domain\Driver\Models\Driver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElmOrder extends Model
{
    use HasFactory;

    protected $fillable=[
      'order_id',
      'elm_id',
      'authorityId',
      'categoryId',
      'deliveryTime',
      'elm_upload_status',
      'acceptanceDateTime',
      'elm_errors',
    ];
    
    protected $casts = [
      'elm_errors' => 'array'
  ];

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantOrder::class,'order_id','id');
    }

}
