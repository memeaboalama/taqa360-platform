<?php

namespace Domain\Driver\Models;

use App\Domain\AppBase\Models\Bank;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverBankInfo extends Model
{
    use HasFactory;

    protected $table='driver_bank_info';
    protected $fillable=[
      'name_on_card',
      'bank_name',
      'card_number',
      'cvv',
      'expire_month',
      'expire_year',
      'bank_id',
      'driver_id',

    ];

    public function bank(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Bank::class,'bank_id','id');
    }

    public function driver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Driver::class,'driver_id','id');
    }
}
