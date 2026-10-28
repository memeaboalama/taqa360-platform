<?php

namespace Domain\SalesAgent\Models;

use App\Domain\AppBase\Models\Bank;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesAgentBankInfo extends Model
{
    use HasFactory;
    protected $table='sales_agent_bank_info';
    protected $fillable=[
      'name_on_card',
      'bank_name',
      'card_number',
      'cvv',
      'expire_month',
      'expire_year',
      'bank_id',
      'sales_agent_id',
    ];

    public function bank(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Bank::class,'bank_id','id');
    }

    public function driver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SalesAgent::class,'sales_agent_id','id');
    }
}
