<?php

namespace Domain\MarketPlace\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantBranchWorkdayHour extends Model
{
    use HasFactory;

    protected $fillable = ["from", "to", 'status','merchant_branch_id','work_day_id'];
    protected $table="merchant_branches_work_day_hours";
    public function workday(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantBranchWorkday::class, 'work_day_id', 'id');
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantBranch::class, 'merchant_branch_id', 'id');
    }
}
